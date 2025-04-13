<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class MessagingViewController extends Controller
{
    /**
     * Show the appropriate messaging view based on device type and orientation.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        // Force mobile view if requested in query params
        if ($request->has('mobile')) {
            return view('messages-mobile');
        }
        
        // Force desktop view if requested in query params
        if ($request->has('desktop')) {
            return view('messages');
        }

        $agent = new Agent();
        
        // If it's a mobile device, show the mobile view
        if ($agent->isMobile() || $agent->isTablet()) {
            // Check for portrait orientation using common mobile widths
            // Modern mobile browsers send viewport dimensions in the user agent
            if ($request->header('sec-ch-viewport-width') && $request->header('sec-ch-viewport-height')) {
                $width = (int) $request->header('sec-ch-viewport-width');
                $height = (int) $request->header('sec-ch-viewport-height');
                
                // If width is less than height, it's in portrait mode
                if ($width < $height) {
                    return view('messages-mobile');
                }
            }
            
            // For browsers that don't provide viewport dimensions,
            // we'll use common device detection based on user agent
            if ($agent->isPhone()) {
                return view('messages-mobile');
            }
        }
        
        // Default to desktop view
        return view('messages');
    }
}