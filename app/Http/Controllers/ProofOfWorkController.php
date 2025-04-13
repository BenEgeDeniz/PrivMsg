<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class ProofOfWorkController extends Controller
{
    /**
     * The default difficulty level (number of leading zeros required)
     */
    protected $defaultDifficulty = 3;
    
    /**
     * The mobile difficulty level (reduced for mobile devices)
     */
    protected $mobileDifficulty = 2;
    
    /**
     * Time in seconds that a challenge is valid
     */
    protected $challengeExpiry = 180; // 3 minutes

    /**
     * Generate a new proof of work challenge
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateChallenge(Request $request)
    {
        // Generate a random challenge string
        $challenge = Str::random(32);
        
        // Generate a random nonce prefix (makes it harder to reuse solutions)
        $noncePrefix = Str::random(8);
        
        // Detect if the request is coming from a mobile device
        $agent = new Agent();
        $agent->setUserAgent($request->header('User-Agent'));
        
        // Set the difficulty based on device type
        $difficulty = $agent->isMobile() || $agent->isTablet() 
            ? $this->mobileDifficulty 
            : $this->defaultDifficulty;
        
        // Store the challenge in cache with expiration time
        // Also store the difficulty with the challenge for verification later
        Cache::put("pow_challenge:{$challenge}", [
            'noncePrefix' => $noncePrefix,
            'difficulty' => $difficulty
        ], $this->challengeExpiry);
        
        return response()->json([
            'challenge' => $challenge,
            'noncePrefix' => $noncePrefix,
            'difficulty' => $difficulty,
            'expires_in' => $this->challengeExpiry
        ]);
    }

    /**
     * Verify a proof of work solution
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyChallenge(Request $request)
    {
        $request->validate([
            'challenge' => 'required|string',
            'nonce' => 'required|string',
        ]);

        $challenge = $request->input('challenge');
        $nonce = $request->input('nonce');
        
        // Get the challenge data from cache
        $challengeData = Cache::get("pow_challenge:{$challenge}");
        
        // Check if challenge exists and hasn't expired
        if (!$challengeData || !isset($challengeData['noncePrefix'])) {
            return response()->json([
                'valid' => false,
                'message' => 'Challenge expired or invalid'
            ], 400);
        }
        
        $noncePrefix = $challengeData['noncePrefix'];
        $difficulty = $challengeData['difficulty'] ?? $this->defaultDifficulty;
        
        // Verify that the nonce starts with our prefix
        if (!Str::startsWith($nonce, $noncePrefix)) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid nonce prefix'
            ], 400);
        }
        
        // Verify the proof of work
        if ($this->isValidProof($challenge, $nonce, $difficulty)) {
            // Generate a verification token
            $token = Str::random(64);
            
            // Store the token with a short expiry
            Cache::put("pow_verified:{$token}", true, 300); // 5 minutes
            
            // Delete the original challenge
            Cache::forget("pow_challenge:{$challenge}");
            
            return response()->json([
                'valid' => true,
                'verification_token' => $token,
                'message' => 'Proof of work verified successfully'
            ]);
        }
        
        return response()->json([
            'valid' => false,
            'message' => 'Invalid proof of work solution'
        ], 400);
    }
    
    /**
     * Validate that a given nonce satisfies the proof-of-work challenge
     *
     * @param string $challenge
     * @param string $nonce
     * @param int $difficulty
     * @return bool
     */
    protected function isValidProof($challenge, $nonce, $difficulty = null)
    {
        // Use provided difficulty or fall back to default
        $difficulty = $difficulty ?? $this->defaultDifficulty;
        
        $hash = hash('sha256', $challenge . $nonce);
        return substr($hash, 0, $difficulty) === str_repeat('0', $difficulty);
    }

    /**
     * Check if a verification token is valid
     *
     * @param string $token
     * @return bool
     */
    public static function isTokenValid($token)
    {
        return Cache::has("pow_verified:{$token}");
    }

    /**
     * Invalidate a verification token after use
     *
     * @param string $token
     * @return void
     */
    public static function invalidateToken($token)
    {
        Cache::forget("pow_verified:{$token}");
    }
}
