/**
 * ApiService.js - Handles all API communication with the backend
 */

class ApiService {
  /**
   * Creates a new HTTP request with CSRF protection
   * @param {string} url - API endpoint URL
   * @param {Object} options - Request options
   * @returns {Promise<Object>} - JSON response
   */
  async request(url, options = {}) {
    // Get the CSRF token from the meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Set default headers
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      ...options.headers
    };
    
    // Add CSRF token for non-GET requests
    if (csrfToken && options.method && options.method !== 'GET') {
      headers['X-CSRF-TOKEN'] = csrfToken;
    }
    
    // Log the request details for debugging
    if (options.method === 'POST' && options.body) {
      console.log('API request to:', url, 'with body:', options.body);
    }
    
    // Make the request
    const response = await fetch(url, {
      ...options,
      headers
    });
    
    // Handle JSON responses
    if (response.headers.get('Content-Type')?.includes('application/json')) {
      const data = await response.json();
      
      // If response is not OK, throw an error with the response data
      if (!response.ok) {
        const error = new Error(data.message || 'API request failed');
        error.response = response;
        error.data = data;
        throw error;
      }
      
      return data;
    }
    
    // Handle non-JSON responses
    if (!response.ok) {
      const error = new Error('API request failed');
      error.response = response;
      throw error;
    }
    
    return response;
  }
  
  // Key Pair Management
  
  /**
   * Store the user's public key on the server
   * @param {string} publicKey - Base64 encoded public key
   * @returns {Promise<Object>} - Server response
   */
  async storePublicKey(publicKey) {
    return this.request('/api/keypair', {
      method: 'POST',
      body: JSON.stringify({
        public_key: publicKey
      })
    });
  }
  
  /**
   * Check if the user has a key pair registered on the server
   * @returns {Promise<Object>} - Server response with key pair status
   */
  async checkKeyPairStatus() {
    return this.request('/api/keypair');
  }
  
  /**
   * Get a user's public key
   * @param {number} userId - The ID of the user
   * @returns {Promise<Object>} - Server response with the user's public key
   */
  async getUserPublicKey(userId) {
    return this.request(`/api/keypair/${userId}`);
  }
  
  // User Management
  
  /**
   * Get a specific user's information
   * @param {number} userId - The ID of the user
   * @returns {Promise<Object>} - Server response with user info
   */
  async getUser(userId) {
    return this.request(`/api/users/${userId}`);
  }
  
  /**
   * Search for a user by exact username match
   * @param {string} username - The exact username to search for
   * @returns {Promise<Object>} - Server response with matching user (if any)
   */
  async searchUserByExactUsername(username) {
    return this.request(`/api/users/search-exact?username=${encodeURIComponent(username)}`);
  }
  
  // Message Management
  
  /**
   * Send an encrypted message to another user
   * @param {number} recipientId - The ID of the recipient
   * @param {string} encryptedContent - The encrypted message content
   * @param {string} signature - The message signature
   * @param {boolean} isEphemeral - Whether the message should disappear after reading
   * @returns {Promise<Object>} - Server response
   */
  async sendMessage(recipientId, encryptedContent, signature, isEphemeral = false) {
    console.log('ApiService.sendMessage called with ephemeral flag:', isEphemeral, 'type:', typeof isEphemeral);
    
    // Ensure isEphemeral is a boolean
    const ephemeralFlag = Boolean(isEphemeral);
    
    const requestBody = {
      recipient_id: recipientId,
      encrypted_content: encryptedContent,
      signature: signature,
      is_ephemeral: ephemeralFlag
    };
    
    console.log('ApiService.sendMessage request body:', requestBody, 'is_ephemeral type:', typeof ephemeralFlag);
    
    return this.request('/api/messages', {
      method: 'POST',
      body: JSON.stringify(requestBody)
    });
  }
  
  /**
   * Get all user's messages
   * @returns {Promise<Object>} - Server response with messages
   */
  async getMessages() {
    return this.request('/api/messages');
  }
  
  /**
   * Poll for new messages
   * @param {number} lastMessageId - The ID of the last received message
   * @returns {Promise<Object>} - Server response with new messages
   */
  async pollMessages(lastMessageId = 0) {
    return this.request(`/api/messages/poll?last_message_id=${lastMessageId}`);
  }
  
  /**
   * Mark a message as read
   * @param {number} messageId - The ID of the message
   * @returns {Promise<Object>} - Server response
   */
  async markMessageAsRead(messageId) {
    return this.request(`/api/messages/${messageId}/read`, {
      method: 'PATCH',
      body: JSON.stringify({})
    });
  }
  
  // Conversation Management
  
  /**
   * Get all user's conversations
   * @returns {Promise<Object>} - Server response with conversations
   */
  async getConversations() {
    return this.request('/api/conversations');
  }
  
  /**
   * Get messages for a specific conversation
   * @param {number} userId - The ID of the other user in the conversation
   * @returns {Promise<Object>} - Server response with conversation messages
   */
  async getConversation(userId) {
    return this.request(`/api/conversations/${userId}`);
  }
  
  // Typing Indicators
  
  /**
   * Send a typing indicator to another user
   * @param {number} userId - The ID of the user to send the typing indicator to
   * @param {boolean} isTyping - Whether the user is typing or stopped typing
   * @returns {Promise<Object>} - Server response
   */
  async sendTypingIndicator(userId, isTyping) {
    return this.request('/api/typing-status', {
      method: 'POST',
      body: JSON.stringify({
        recipient_id: userId,
        is_typing: isTyping
      })
    });
  }
  
  /**
   * Poll for typing indicators
   * @returns {Promise<Object>} - Server response with current typing statuses
   */
  async getTypingIndicators() {
    return this.request('/api/typing-status');
  }
  
  /**
   * Delete a conversation and all messages with a user
   * @param {number} userId - The ID of the other user in the conversation
   * @returns {Promise<Object>} - Server response
   */
  async deleteConversation(userId) {
    return this.request(`/api/conversations/${userId}`, {
      method: 'DELETE'
    });
  }

  /**
   * Delete a specific message
   * @param {number} messageId - The ID of the message to delete
   * @returns {Promise<Object>} - Server response
   */
  async deleteMessage(messageId) {
    return this.request(`/api/messages/${messageId}`, {
      method: 'DELETE'
    });
  }
}

// Create and export a singleton instance
const apiService = new ApiService();
export default apiService;