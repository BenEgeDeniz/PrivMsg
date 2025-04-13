/**
 * MobileMessaging.vue - Mobile-optimized end-to-end encrypted messaging component
 * Designed specifically for vertical mobile device orientations
 */
<template>
  <div class="h-full flex flex-col bg-gray-900 overflow-hidden">
    <!-- Loading state -->
    <div v-if="!keysReady && !keyError" class="h-full flex items-center justify-center">
      <div class="text-center">
        <svg class="animate-spin h-10 w-10 text-blue-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-white">Loading encryption keys...</p>
      </div>
    </div>
    
    <!-- Key error state -->
    <div v-else-if="keyError" class="h-full flex items-center justify-center p-6">
      <div class="text-center max-w-md">
        <svg class="h-12 w-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <h2 class="text-xl font-bold text-white mb-2">Encryption Keys Required</h2>
        <p class="text-gray-400 mb-6">
          You need to configure your encryption keys before you can send or receive secure messages.
          Please tap the settings icon to set up your keys.
        </p>
        <a href="/settings" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Go to Settings
        </a>
      </div>
    </div>
    
    <!-- Messaging interface -->
    <div v-else class="h-full flex flex-col overflow-hidden">
      <!-- View switcher - either conversation list or active conversation -->
      <div v-if="selectedUserId === null" class="h-full flex flex-col overflow-hidden">
        <!-- Conversation list header -->
        <div class="bg-gray-800 px-4 py-3 flex justify-between items-center shadow flex-shrink-0">
          <!-- PrivMsg Logo replacing text -->
          <div class="flex items-center">
            <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <span class="ml-2 text-xl font-bold text-white">PrivMsg</span>
          </div>
          
          <div class="flex items-center space-x-2">
            <!-- Settings button (moved from fixed position) -->
            <a 
              href="/settings" 
              class="p-2 rounded-full bg-gray-700 text-white settings-button"
              aria-label="Settings"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
            </a>
            
            <!-- New message button (now second) -->
            <button 
              @click="showNewMessageModal = true" 
              class="p-2 rounded-full bg-blue-600 text-white new-message-button"
              aria-label="New message"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
            </button>
          </div>
        </div>
        
        <!-- Conversation list -->
        <div class="flex-1 overflow-y-auto bg-gray-900 scrollable">
          <div v-if="loadingConversations" class="flex justify-center items-center h-32">
            <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>
          
          <div v-else-if="conversations.length === 0" class="flex flex-col items-center justify-center h-64 text-center px-4">
            <svg class="w-16 h-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <p class="text-gray-400">No messages yet</p>
            <p class="text-gray-500 text-sm mt-2">Start a new conversation to send encrypted messages</p>
          </div>
          
          <div v-else>
            <!-- Each conversation -->
            <div
              v-for="conversation in conversations"
              :key="conversation.user.id"
              class="relative w-full overflow-hidden"
            >
              <!-- Delete button that appears on swipe -->
              <div 
                class="absolute right-0 top-0 h-full flex items-center px-4 bg-red-600 delete-button-container"
                @click.stop="(e) => handleDeleteClick(conversation.user.id, e)"
              >
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
              </div>
              
              <div
                :data-conversation-id="conversation.user.id"
                class="w-full transition-transform duration-200 ease-out bg-gray-900"
                @touchstart="handleConversationTouchStart($event, conversation.user.id)"
                @touchmove="handleConversationTouchMove"
                @touchend="handleConversationTouchEnd"
              >
                <div
                  @click="selectConversation(conversation.user.id)"
                  class="w-full text-left px-4 py-3 border-b border-gray-800 flex items-center cursor-pointer"
                  :class="{ 'bg-gray-800': selectedUserId === conversation.user.id }"
                >
                  <!-- User avatar/initial -->
                  <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-medium text-lg mr-3">
                    {{ conversation.user.username.charAt(0).toUpperCase() }}
                  </div>
                  
                  <!-- Content -->
                  <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-center">
                      <h3 class="text-white font-medium truncate">{{ conversation.user.username }}</h3>
                      <span class="text-xs text-gray-500">
                        {{ formatConversationTime(conversation.latest_message?.created_at) }}
                      </span>
                    </div>
                    <div class="flex items-center">
                      <!-- Preview of the latest message -->
                      <p class="text-sm text-gray-400 truncate flex-1">
                        <span v-if="conversation.latest_message && conversation.latest_message.sender_id === currentUserId">
                          <span class="text-blue-400">You: </span> 
                        </span>
                        <span v-if="decryptedMessages[conversation.latest_message?.id]">
                          {{ decryptedMessages[conversation.latest_message.id] }}
                        </span>
                        <span v-else-if="conversation.latest_message">
                          [Encrypted message]
                        </span>
                        <span v-else class="italic">No messages yet</span>
                      </p>
                      
                      <!-- Unread badge -->
                      <span 
                        v-if="conversation.unread_count && conversation.unread_count > 0" 
                        class="ml-2 px-2 py-0.5 bg-blue-600 text-white text-xs rounded-full unread-badge"
                      >
                        {{ Math.ceil(conversation.unread_count / 2) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Active conversation view -->
      <transition name="conversation-slide" @after-leave="handleAfterLeave">
        <div v-if="selectedUserId" 
             class="h-full flex flex-col overflow-hidden conversation-layout"
             @touchstart="handleTouchStart"
             @touchmove="handleTouchMove"
             @touchend="handleTouchEnd">
          <!-- Conversation header - Fixed at top -->
          <header class="conversation-header bg-gray-800 px-4 py-3 flex flex-col shadow">
            <div class="flex justify-between items-center">
              <div class="flex items-center">
                <button 
                  @click="backToConversations" 
                  class="mr-2 p-1 text-gray-400 hover:text-white focus:outline-none back-button"
                >
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                </button>
                
                <div class="flex items-center">
                  <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-medium text-base mr-2">
                    {{ selectedUserName.charAt(0).toUpperCase() }}
                  </div>
                  <div>
                    <h2 class="text-white font-medium">{{ selectedUserName }}</h2>
                    <div v-if="typingUsers && typingUsers[selectedUserId]" class="text-xs text-gray-400 flex items-center">
                      <span>typing</span>
                      <div class="flex items-center ml-1">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                      </div>
                    </div>
                    <p v-else class="text-xs text-gray-400">End-to-end encrypted</p>
                  </div>
                </div>
              </div>
              
              <div>
                <button 
                  @click="handleConversationDelete"
                  class="p-2 text-gray-400 hover:text-red-500 inline-block delete-button"
                  aria-label="Delete conversation"
                >
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </div>
          </header>
          
          <!-- Messages area - Constrained between header and input -->
          <div ref="messageList" class="messages-container flex-1 overflow-y-auto p-4 space-y-3 scrollable" @scroll="handleScroll">
            <div v-if="loadingMessages" class="flex justify-center py-4">
              <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
            
            <div v-else-if="messages.length === 0" class="flex flex-col items-center justify-center h-64 text-center">
              <svg class="w-16 h-16 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
              </svg>
              <p class="text-gray-400">No messages yet</p>
              <p class="text-gray-500 text-sm mt-1">Send a message to start the conversation</p>
            </div>
            
            <template v-else>
              <!-- Messages -->
              <div 
                v-for="message in messages" 
                :key="message.id" 
                :class="[
                  'flex',
                  message.sender_id === currentUserId ? 'justify-end' : 'justify-start'
                ]"
                @click="handleMessageClick"
              >
                <div 
                  class="rounded-2xl px-4 py-2 max-w-[85%] shadow message-bubble" 
                  :class="message.sender_id === currentUserId 
                    ? 'bg-blue-600 text-white rounded-br-none' 
                    : 'bg-gray-700 text-white rounded-bl-none'"
                  @touchstart.prevent="handleMessageTouchStart($event, message)"
                  @touchend.prevent="handleMessageTouchEnd"
                  @touchmove.prevent="handleMessageTouchMove"
                  @contextmenu.prevent
                >
                  <!-- Ephemeral message indicator -->
                  <div v-if="message.is_ephemeral" class="flex items-center mb-1">
                    <span class="text-xs bg-yellow-600 text-yellow-100 px-2 py-0.5 rounded-full flex items-center ephemeral-indicator">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      Ephemeral
                    </span>
                  </div>
                  
                  <!-- Invalid signature warning -->
                  <div v-if="invalidSignatureMessages.includes(message.id)" class="flex items-center mb-1">
                    <span class="text-xs bg-red-600 text-red-100 px-2 py-0.5 rounded-full flex items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                      </svg>
                      Invalid Signature
                    </span>
                  </div>
                  
                  <!-- Message content -->
                  <div v-if="decryptedMessages[message.id]" class="whitespace-pre-wrap break-words text-sm">
                    <template v-if="invalidSignatureMessages.includes(message.id)">
                      <div class="p-2 mb-2 bg-red-900 bg-opacity-50 rounded text-red-100 text-xs">
                        Warning: This message's signature could not be verified. The contents may have been tampered with.
                      </div>
                    </template>
                    {{ decryptedMessages[message.id] }}
                  </div>
                  <div v-else class="text-sm text-gray-300 italic flex items-center">
                    <svg class="animate-spin inline-block h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Decrypting...
                  </div>
                  
                  <!-- Timestamp -->
                  <div class="text-xs opacity-70 mt-1 text-right message-timestamp">
                    {{ formatTimestamp(message.created_at) }}
                  </div>
                </div>
              </div>
            </template>
    </div>
          
          <!-- Message input - Fixed at bottom -->
          <div class="message-input-container bg-gray-800 p-3 border-t border-gray-700" :class="{ 'sending': sendingMessage }">
            <form @submit.prevent="sendMessage" class="flex flex-col space-y-2">
              <div class="flex items-end space-x-2">
                <div class="flex-1 bg-gray-700 rounded-full px-4 py-2 flex items-end">
                  <textarea
                    ref="messageInput"
                    v-model="newMessage"
                    @input="handleTyping"
                    placeholder="Message"
                    class="flex-1 bg-transparent text-white placeholder-gray-400 focus:outline-none resize-none max-h-24 leading-5 text-sm py-1"
                    :disabled="sendingMessage"
                    rows="1"
                  ></textarea>
                </div>
                
                <!-- Ephemeral message button -->
                <button 
                  type="button"
                  @click="isEphemeral = !isEphemeral"
                  class="p-3 rounded-full text-white flex-shrink-0 ephemeral-toggle"
                  :class="{ 
                    'bg-yellow-600 hover:bg-yellow-500': isEphemeral, 
                    'bg-gray-700 hover:bg-gray-600': !isEphemeral,
                    'active': isEphemeral
                  }"
                  aria-label="Toggle ephemeral message"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </button>
                
                <button
                  type="submit"
                  :disabled="sendingMessage || !newMessage.trim()"
                  class="p-3 rounded-full text-white disabled:opacity-50 flex-shrink-0 send-button"
                  :class="{ 
                    'bg-blue-600 hover:bg-blue-500': !sendingMessage,
                    'bg-blue-800 hover:bg-blue-700 send-button-loading': sendingMessage
                  }"
                  aria-label="Send message"
                >
                  <span v-if="sendingMessage">
                    <svg class="animate-spin h-5 w-5 send-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                  </span>
                  <span v-else>
                    <svg class="w-5 h-5 send-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                  </span>
                </button>
              </div>
              
              <!-- Ephemeral message info text -->
              <div v-if="isEphemeral" class="flex items-center px-4">
                <span class="text-xs text-gray-400">
                  This message will disappear after being read
                </span>
              </div>
            </form>
          </div>
        </div>
      </transition>
    </div>
    
    <!-- New message modal -->
    <div v-if="showNewMessageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4 modal-enter-active modal-leave-active">
      <div class="bg-gray-800 rounded-lg p-4 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium text-white">New Message</h3>
          <button
            @click="showNewMessageModal = false"
            class="p-1 text-gray-400 hover:text-white focus:outline-none focus:text-white"
            aria-label="Close"
          >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <!-- Search input -->
        <div class="mb-4">
          <form @submit.prevent="searchUser" class="flex space-x-2">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Enter exact username..."
              class="block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm bg-gray-900 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent search-input"
            />
            <button
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
              :disabled="!searchQuery.trim()"
            >
              Search
            </button>
          </form>
        </div>
        
        <div v-if="loadingUsers" class="flex justify-center py-4">
          <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>
        
        <div v-else-if="searchSubmitted && filteredUsers.length === 0" class="text-gray-400 text-center py-4">
          <p>User not found</p>
        </div>
        
        <div v-else-if="filteredUsers.length > 0" class="max-h-60 overflow-y-auto border border-gray-700 rounded-md mb-4 scrollable">
          <button
            v-for="user in filteredUsers"
            :key="user.id"
            @click="startConversation(user)"
            class="w-full text-left p-3 border-b border-gray-700 hover:bg-gray-700 focus:outline-none focus:bg-gray-700 last:border-b-0 flex items-center user-result"
          >
            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-medium mr-3">
              {{ user.username.charAt(0).toUpperCase() }}
            </div>
            <span class="font-medium text-white">{{ user.username }}</span>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Delete confirmation modal -->
    <div v-if="showDeleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4 modal-enter-active modal-leave-active">
      <div class="bg-gray-800 rounded-lg p-4 max-w-sm w-full">
        <h3 class="text-lg font-medium text-white mb-4">Delete Conversation</h3>
        <p class="text-gray-400 mb-6">Are you sure you want to delete this conversation? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
          <button
            @click="() => { showDeleteConfirmModal = false; deletionTargetId = null; }"
            class="px-4 py-2 border border-gray-600 text-gray-300 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Cancel
          </button>
          <button
            @click="deleteConversation"
            class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
    
    <!-- Add this new modal for message context menu -->
    <div v-if="showMessageContextModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4 modal-enter-active">
      <div class="bg-gray-800 rounded-lg p-4 w-full max-w-xs">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium text-white">Message Options</h3>
          <button
            @click="closeMessageContextMenu"
            class="p-1 text-gray-400 hover:text-white focus:outline-none focus:text-white"
            aria-label="Close"
          >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div class="space-y-2">
          <button
            @click="showMessageInfo(selectedMessage)"
            class="w-full px-4 py-2 text-left text-white hover:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center"
          >
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Message Info
          </button>
          <button
            @click="copyMessage(selectedMessage)"
            class="w-full px-4 py-2 text-left text-white hover:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center"
          >
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
            </svg>
            Copy Message
          </button>
          <button
            @click="deleteMessage(selectedMessage)"
            class="w-full px-4 py-2 text-left text-red-400 hover:text-red-300 hover:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center"
          >
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Delete Message
          </button>
        </div>
      </div>
    </div>
    
    <!-- Message info modal -->
    <div v-if="showMessageInfoModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4 modal-enter-active">
      <div class="bg-gray-800 rounded-lg p-4 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium text-white">Message Details</h3>
          <button
            @click="closeMessageInfoModal"
            class="p-1 text-gray-400 hover:text-white focus:outline-none focus:text-white"
            aria-label="Close"
          >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div class="space-y-4 text-sm">
          <!-- Sender -->
          <div>
            <h4 class="text-gray-400 mb-1">Sender</h4>
            <p class="text-white">{{ selectedMessage?.sender_id === currentUserId ? 'You' : selectedUserName }}</p>
          </div>
          
          <!-- Recipient -->
          <div>
            <h4 class="text-gray-400 mb-1">Recipient</h4>
            <p class="text-white">{{ selectedMessage?.recipient_id === currentUserId ? 'You' : selectedUserName }}</p>
          </div>
          
          <!-- Timestamp -->
          <div>
            <h4 class="text-gray-400 mb-1">Sent</h4>
            <p class="text-white">{{ formatDetailedTimestamp(selectedMessage?.created_at) }}</p>
          </div>
          
          <!-- Read status -->
          <div>
            <h4 class="text-gray-400 mb-1">Status</h4>
            <div class="flex items-center">
              <span 
                class="inline-flex items-center px-2 py-1 rounded-full text-xs"
                :class="selectedMessage?.is_read ? 'bg-green-900 text-green-300' : 'bg-yellow-900 text-yellow-300'"
              >
                {{ selectedMessage?.is_read ? 'Read' : 'Unread' }}
              </span>
              <span 
                v-if="selectedMessage?.is_ephemeral"
                class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-900 text-yellow-300"
              >
                Ephemeral
              </span>
            </div>
          </div>
          
          <!-- Signature verification -->
          <div>
            <h4 class="text-gray-400 mb-1">Signature</h4>
            <div>
              <div class="flex items-center mb-2">
                <span 
                  class="inline-flex items-center px-2 py-1 rounded-full text-xs mr-2"
                  :class="invalidSignatureMessages.includes(selectedMessage?.id) 
                    ? 'bg-red-900 text-red-100' 
                    : signatureVerifications[selectedMessage?.id] === true 
                      ? 'bg-green-900 text-green-100' 
                      : 'bg-yellow-900 text-yellow-100'"
                >
                  <svg v-if="invalidSignatureMessages.includes(selectedMessage?.id)" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  <svg v-else-if="signatureVerifications[selectedMessage?.id] === true" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  {{ invalidSignatureMessages.includes(selectedMessage?.id) 
                    ? 'Invalid Signature' 
                    : signatureVerifications[selectedMessage?.id] === true 
                      ? 'Verified Signature' 
                      : 'Unverified (Refreshing may help)' }}
                </span>
              </div>
              <p class="text-white break-all font-mono text-xs">{{ selectedMessage?.signature }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import cryptoService from '../services/CryptoService';
import apiService from '../services/ApiService';
import toastService from '../services/ToastService';

// --- Constants ---
const SCROLL_THRESHOLD_RE_ENABLE_AUTO = 100; // px from bottom to re-enable auto-scroll
const TYPING_DEBOUNCE_DELAY_MS = 1000;      // ms to wait before considering user has stopped typing
const MESSAGE_POLLING_INTERVAL_MS = 3000;
const MESSAGE_POLLING_ERROR_RETRY_MS = 5000;
const TYPING_POLLING_INTERVAL_MS = 2500;
const SCROLL_DELAY_MS = 50;                 // Delay for scrolling adjustments
const SCROLL_PADDING_BASE_PX = 30;          // Base padding below last message
const SCROLL_PADDING_SENT_PX = 120;         // Extra padding below sent messages
const KEYBOARD_OPEN_THRESHOLD_PX = 50;      // Min height diff to consider keyboard open
const KEYBOARD_SCROLL_DELAY_MS = 300;       // Delay for scrolling after keyboard change
const TEXTAREA_MAX_HEIGHT_PX = 120;
const LOCAL_STORAGE_SENT_MESSAGES_KEY = 'sentMessages';
const FALLBACK_SENT_MESSAGE_TEXT = "Sent message (not stored locally)";
const DECRYPTION_FAILED_TEXT = "[Decryption failed]";
const INITIAL_SCROLL_OFFSET = 1000; // Large value to ensure initial scroll hits bottom
const IS_IOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

export default {
  data() {
    return {
      // User and key state
      currentUserId: parseInt(document.querySelector('meta[name="user-id"]')?.getAttribute('content')),
      username: document.querySelector('meta[name="username"]')?.getAttribute('content'),
      keysReady: false,
      keyError: false,
      
      // Conversations
      conversations: [],
      loadingConversations: true,
      
      // Messages
      messages: [],
      decryptedMessages: {},
      loadingMessages: false,
      selectedUserId: null,
      selectedUserName: '',
      newMessage: '',
      sendingMessage: false,
      lastMessageId: 0,
      isEphemeral: false,
      
      // Signature verification
      signatureVerifications: {}, // Maps message ID to verification status (true, false, or 'pending')
      invalidSignatureMessages: [], // Array of message IDs with invalid signatures
      
      // Audio notification
      notificationSound: null,
      
      // Scroll management
      userHasScrolled: false,
      autoScrollEnabled: true,
      scrollTimeout: null,
      keyboardTimeout: null,
      
      // Typing indicators
      typingUsers: {},        // Maps user IDs to typing status
      isTyping: false,        // Current user's typing status
      typingTimeout: null,    // Timeout to manage typing status
      typingPollingActive: false,
      typingPollingTimeout: null,
      typingDebounceDelay: TYPING_DEBOUNCE_DELAY_MS, // Use constant
      
      // Users
      users: [],
      loadingUsers: false,
      
      // UI state
      showNewMessageModal: false,
      showDeleteConfirmModal: false,
      
      // Long polling
      pollingActive: false,
      pollingTimeout: null,
      
      // Search
      searchQuery: '',
      filteredUsers: [],
      searchSubmitted: false,
      
      // Internal state for UI management
      _lastKeyboardHeight: null,
      _keyboardOpenState: null,
      _scrollTimeouts: [],
      _keyboardTimeout: null,
      
      // iOS specific flags
      isIOS: IS_IOS,
      safeAreaBottom: 0,
      viewportHeight: window.innerHeight,
      keyboardHeight: 0,
      fullscreenActive: false,

      // Window visibility tracking
      isWindowFocused: document.visibilityState === 'visible',
      pendingReadMessages: [], // Store messages pending to be marked as read
      
      // Swipe gesture tracking
      touchState: {
        isActive: false,
        startTime: 0,
        startX: 0,
        startY: 0,
        lastX: 0,
        lastY: 0
      },
      
      // Swipe-to-close state
      swipeProgress: 0,
      isSwiping: false,
      swipeThreshold: 100, // Minimum swipe distance to trigger close
      swipeMaxY: 50, // Maximum vertical movement allowed during swipe
      velocityThreshold: 0.3, // Minimum velocity to trigger close
      
      // Add new state for deletion
      deletionTargetId: null,
      
      // Swipe-to-delete state
      swipeDeleteState: {
        startX: 0,
        currentX: 0,
        activeId: null,
        isSwiping: false,
        threshold: 50 // Minimum swipe distance to trigger delete button
      },
      
      // Message context menu state
      showMessageContextModal: false,
      selectedMessage: null,
      
      // Long press handling
      longPressTimeout: null,
      longPressDelay: 500, // 500ms for long press
      longPressStartPosition: null,
      
      // Message info modal state
      showMessageInfoModal: false,
    };
  },
  async mounted() {
    // Initialize notification sound
    this.notificationSound = new Audio('/notify.mp3');
    
    await this.initializeKeys();
    
    if (this.keysReady) {
      this.loadConversations();
      this.startMessagePolling();
    }
    
    // Auto-resize textarea
    this.setupTextareaAutoResize();
    
    // Apply initial safe area insets for iOS devices
    this.updateSafeAreaInsets();
    
    // Setup visual viewport event listeners
    if (window.visualViewport) {
      window.visualViewport.addEventListener('resize', this.handleVisualViewportResize);
      window.visualViewport.addEventListener('scroll', this.handleVisualViewportResize);
    }
    
    // Add window resize listener (for orientation changes)
    window.addEventListener('resize', this.handleWindowResize);
    
    // Setup iOS-specific focus listeners
    if (this.isIOS) {
      document.addEventListener('focusin', this.handleIOSFocusIn);
      document.addEventListener('focusout', this.handleIOSFocusOut);
    }
    
    // Prevent elastic scrolling on iOS
    this.preventIOSElasticScrolling();

    // Setup visibility change listener to track window focus
    document.addEventListener('visibilitychange', this.handleVisibilityChange);
    
    // Add touch event listeners
    const conversationContainer = document.querySelector('.conversation-layout');
    if (conversationContainer) {
      conversationContainer.addEventListener('touchstart', this.handleTouchStart, { passive: true });
      conversationContainer.addEventListener('touchmove', this.handleTouchMove, { passive: true });
      conversationContainer.addEventListener('touchend', this.handleTouchEnd, { passive: true });
    }
    
    // Add iOS-specific viewport listeners
    if (this.isIOS && window.visualViewport) {
      window.visualViewport.addEventListener('resize', this.handleIOSKeyboard);
      window.visualViewport.addEventListener('scroll', this.handleIOSKeyboard);
    }
  },
  beforeUnmount() {
    this.stopMessagePolling();
    this.stopTypingIndicatorPolling();
    
    // Clean up event listeners
    if (this.$refs.messageInput) {
      this.$refs.messageInput.removeEventListener('input', this.autoResizeTextarea);
    }
    
    if (window.visualViewport) {
      window.visualViewport.removeEventListener('resize', this.handleVisualViewportResize);
      window.visualViewport.removeEventListener('scroll', this.handleVisualViewportResize);
    }
    
    window.removeEventListener('resize', this.handleWindowResize);
    
    if (this.isIOS) {
      document.removeEventListener('focusin', this.handleIOSFocusIn);
      document.removeEventListener('focusout', this.handleIOSFocusOut);
    }
    
    // Clean up visibility change listener
    document.removeEventListener('visibilitychange', this.handleVisibilityChange);
    
    // Clear any pending scroll timeouts
    this._clearScrollTimeouts();
    if (this._keyboardTimeout) clearTimeout(this._keyboardTimeout);
    
    // Remove touch event listeners
    const conversationContainer = document.querySelector('.conversation-layout');
    if (conversationContainer) {
      conversationContainer.removeEventListener('touchstart', this.handleTouchStart);
      conversationContainer.removeEventListener('touchmove', this.handleTouchMove);
      conversationContainer.removeEventListener('touchend', this.handleTouchEnd);
    }
    
    // Remove iOS-specific listeners
    if (this.isIOS && window.visualViewport) {
      window.visualViewport.removeEventListener('resize', this.handleIOSKeyboard);
      window.visualViewport.removeEventListener('scroll', this.handleIOSKeyboard);
    }
  },
  watch: {
    // When a user is selected, load their conversation
    async selectedUserId(newVal) {
      if (newVal) {
        this.loadMessages(newVal);
      }
    },
    
    // Modify the messages watcher to be more selective about scrolling
    messages: {
      handler(newMessages, oldMessages) {
        const isInitialLoad = !oldMessages || oldMessages.length === 0;
        const hasNewMessages = !isInitialLoad && newMessages.length > oldMessages.length;
        const isEarlyMessages = newMessages.length <= 9;
        
        this.$nextTick(() => {
          this.decryptMessages();

          if (isInitialLoad) {
            // For initial load, use immediate scroll
            this.scrollToBottom(true, false);
            return;
          }

          if (hasNewMessages) {
            const newestMessage = newMessages[newMessages.length - 1];
            const isSentByUser = newestMessage.sender_id === this.currentUserId;
            const shouldScroll = isSentByUser || this.autoScrollEnabled;

            if (shouldScroll) {
              // For early messages, use more aggressive scrolling
              if (isEarlyMessages) {
                this.scrollToBottom(true, isSentByUser);
                setTimeout(() => this.scrollToBottom(true, isSentByUser), 100);
              } else {
                this.scrollToBottom(true, isSentByUser);
              }
            }
          }
        });
      },
      deep: true
    },
    
    // When decrypted message content changes, only scroll under specific conditions
    decryptedMessages: {
      handler() {
        // No scroll needed here - handled by `messages` watcher for new items
      },
      deep: true
    },
    
    // Load users when the New Message modal is opened
    showNewMessageModal(isOpen) {
      if (isOpen) {
        this.loadUsers();
        this.searchQuery = '';
        this.filteredUsers = [];
        this.searchSubmitted = false;
      }
    }
  },
  methods: {
    // --- Lifecycle & Initialization ---

    async initializeKeys() {
      try {
        // Check if we have keys in the browser's crypto context
        if (window.cryptoContext && window.cryptoContext.privateKey) {
          this.keysReady = true;
          return;
        }
        
        // Try to retrieve keys from IndexedDB
        const storedKeys = await cryptoService.retrieveKeyPair(this.username);
        
        if (storedKeys) {
          // We have keys in browser storage
          window.cryptoContext = {
            privateKey: storedKeys.privateKey,
            publicKey: storedKeys.publicKey,
            exportedPrivateKey: storedKeys.exportedPrivateKey,
            exportedPublicKey: storedKeys.exportedPublicKey
          };
          this.keysReady = true;
        } else {
          // Check if we have a public key on the server
          const keyStatus = await apiService.checkKeyPairStatus();
          
          if (!keyStatus.has_key_pair) {
            // No keys available
            this.keyError = true;
          } else {
            // We have a public key on the server but no private key locally
            // User needs to import their private key on the dashboard
            this.keyError = true;
          }
        }
      } catch (error) {
        console.error('Error initializing keys:', error);
        this.keyError = true;
      }
    },

    setupTextareaAutoResize() {
      if (this.$refs.messageInput) {
        this.$refs.messageInput.addEventListener('input', this.autoResizeTextarea);
      }
    },
    
    // --- Conversation Management ---

    async loadConversations() {
      try {
        this.loadingConversations = true;
        const response = await apiService.getConversations();
        this.conversations = response.conversations;
        
        // Pre-decrypt the latest messages in conversations
        for (const conversation of this.conversations) {
          if (conversation.latest_message) {
            this.decryptMessage(conversation.latest_message);
          }
        }
      } catch (error) {
        console.error('Error loading conversations:', error);
      } finally {
        this.loadingConversations = false;
      }
    },
    
    async loadUsers() {
      // No longer loading all users - we'll only get users via exact search
      this.loadingUsers = false;
    },
    
    async selectConversation(userId) {
      this.selectedUserId = userId;
      
      // Find the user's name from the conversations list
      const conversation = this.conversations.find(c => c.user.id === userId);
      if (conversation) {
        this.selectedUserName = conversation.user.username;
      }
      
      // Start polling for typing indicators when a conversation is selected
      this.startTypingIndicatorPolling();
    },
    
    async startConversation(user) {
      this.selectedUserId = user.id;
      this.selectedUserName = user.username;
      this.showNewMessageModal = false;
      this.messages = [];
      
      // Mark this as a new conversation if it doesn't exist
      if (!this.conversations.find(c => c.user.id === user.id)) {
        this.conversations.push({
          user: user,
          latest_message: null,
          unread_count: 0
        });
      }
      
      // Start polling for typing indicators
      this.startTypingIndicatorPolling();
    },
    
    async deleteConversation() {
      const targetId = this.deletionTargetId || this.selectedUserId;
      
      if (!targetId) {
        console.error('No conversation ID specified for deletion');
        return;
      }

      try {
        // Call API to delete the conversation
        await apiService.deleteConversation(targetId);
        
        // Remove the conversation from the list
        this.conversations = this.conversations.filter(c => c.user.id !== targetId);
        
        // Reset states
        this.deletionTargetId = null;
        this.selectedUserId = null;
        this.stopTypingIndicatorPolling();
        
      } catch (error) {
        console.error('Error deleting conversation:', error);
        toastService.error('Error deleting conversation. Please try again.');
      } finally {
        // Close the confirmation modal
        this.showDeleteConfirmModal = false;
      }
    },

    updateConversationWithLatestMessage(message) {
      const otherUserId = message.sender_id === this.currentUserId ? message.recipient_id : message.sender_id;
      let conversation = this.conversations.find(c => c.user.id === otherUserId);
      
      // If conversation doesn't exist yet, create it
      if (!conversation) {
        // Try to find user in users list
        const user = this.users.find(u => u.id === otherUserId);
        if (user) {
          conversation = {
            user: user,
            latest_message: null,
            unread_count: 0
          };
          this.conversations.push(conversation);
        } else {
          // Can't create conversation without user data
          return;
        }
      }
      
      // Update the latest message
      conversation.latest_message = message;
      
      // Handle unread count
      // If we're the recipient and not currently viewing this conversation, increment count
      if (message.recipient_id === this.currentUserId && !message.is_read && this.selectedUserId !== message.sender_id) {
        conversation.unread_count = (conversation.unread_count || 0) + 1;
      }
      
      // Move this conversation to the top (most recent)
      const index = this.conversations.findIndex(c => c.user.id === otherUserId);
      if (index > 0) {
        // Remove and reinsert at the beginning
        this.conversations.unshift(this.conversations.splice(index, 1)[0]);
      } else if (index === -1) {
        // New conversation, add to beginning
        this.conversations.unshift(conversation);
      }
    },
    
    // --- Message Handling ---

    async loadMessages(userId) {
      try {
        this.loadingMessages = true;
        this.messages = [];
        this.decryptedMessages = {};
        
        const response = await apiService.getConversation(userId);
        this.messages = response.messages.reverse(); // Newest at the bottom
        
        if (this.messages.length > 0) {
          // Update the last message ID for polling
          this.lastMessageId = Math.max(...this.messages.map(m => m.id));
        }
        
        // Decrypt the messages
        await this.decryptMessages();
        
        // Only mark messages as read if window is focused
        if (this.isWindowFocused) {
        await this.markMessagesAsRead();
        } else {
          // Store messages to be marked as read when window gets focus
          this.storePendingReadMessages();
        }
        
        // Update the conversation unread count in the sidebar
        const conversation = this.conversations.find(c => c.user.id === userId);
        if (conversation && conversation.unread_count > 0 && this.isWindowFocused) {
          // Set unread count to 0 only if window is focused
          conversation.unread_count = 0;
        }
        
        // On iOS, need more careful handling of message input focus
        if (this.isIOS) {
          // First make sure the layout is correctly adjusted
          this.updateLayoutForKeyboard();
          
          // Then focus with a delay to ensure UI is ready
          setTimeout(() => {
            if (this.$refs.messageInput) {
              this.$refs.messageInput.focus();
            }
            
            // Multiple scroll attempts for iOS
            setTimeout(() => this._scrollToEnd(true, false), 100);
            setTimeout(() => this._scrollToEnd(true, false), 500);
            setTimeout(() => this._scrollToEnd(true, false), 1000);
          }, 300);
        } else {
          // Non-iOS normal focus and scroll
          if (this.$refs.messageInput) {
            this.$refs.messageInput.focus();
          }
          
          this.$nextTick(() => {
            setTimeout(() => this._scrollToEnd(true, false), 300);
          });
        }
      } catch (error) {
        console.error('Error loading messages:', error);
      } finally {
        this.loadingMessages = false;
      }
    },
    
    async sendMessage() {
      if (!this.newMessage.trim() || !this.selectedUserId || this.sendingMessage) return;
      
      const messageInput = this.$refs.messageInput;
      const messageText = this.newMessage.trim();
      
      // Save the current focus and selection state
      const hadFocus = document.activeElement === messageInput;
      
      try {
        this.sendingMessage = true;
        
        // Save ephemeral state before clearing
        const wasEphemeral = this.isEphemeral;
        
        // Clear the input and reset ephemeral flag
        this.newMessage = '';
        // this.isEphemeral = false;  // Removed to persist ephemeral toggle setting
        
        // Debug ephemeral flag
        console.log('Sending mobile message with ephemeral flag:', wasEphemeral, 'type:', typeof wasEphemeral);
        
        // For iOS: Special focus management to prevent keyboard hiding
        let dummyInput = null;
        if (this.isIOS && hadFocus) {
          // Create a temporary invisible input to maintain keyboard focus
          dummyInput = document.createElement('input');
          dummyInput.setAttribute('type', 'text');
          dummyInput.style.position = 'absolute';
          dummyInput.style.opacity = '0';
          dummyInput.style.height = '0';
          dummyInput.style.fontSize = '16px'; // iOS won't zoom in
          dummyInput.style.top = '0';
          dummyInput.style.left = '0';
          
          // Add it to DOM and focus it
          document.body.appendChild(dummyInput);
          dummyInput.focus();
        }
        
        // Reset textarea height after clearing
        this.autoResizeTextarea();
        
        // Get the recipient's public key
        const keyResponse = await apiService.getUserPublicKey(this.selectedUserId);
        const recipientPublicKey = await cryptoService.importPublicKey(keyResponse.public_key);
        
        // Encrypt the message content
        const encryptedData = await cryptoService.encryptMessage(
          messageText,
          recipientPublicKey,
          window.cryptoContext.privateKey
        );
        
        // Combine all encrypted data into a single JSON string
        const encryptedContent = JSON.stringify({
          message: encryptedData.encryptedMessage,
          key: encryptedData.encryptedKey,
          iv: encryptedData.iv
        });
        
        // Directly send the message with clear ephemeral flag value
        console.log('About to call apiService.sendMessage with ephemeral flag:', wasEphemeral);
        const response = await apiService.sendMessage(
          this.selectedUserId,
          encryptedContent,
          encryptedData.signature,
          wasEphemeral
        );
        
        // Add the sent message to the UI
        const newMessage = response.data;
        console.log('Message sent successfully, response:', newMessage);
        this.messages.push(newMessage);
        this.decryptedMessages[newMessage.id] = messageText;
        this.lastMessageId = newMessage.id;
        
        // Store the sent message locally
        this.storeSentMessage(newMessage.id, messageText);
        
        // Update the conversation list
        this.updateConversationWithLatestMessage(newMessage);
        
        // iOS-specific scroll adjustments
        if (this.isIOS) {
          // Multiple scrolls with delays for iOS
          this._scrollTimeouts.push(setTimeout(() => this._scrollToEnd(true, true), 50));
          this._scrollTimeouts.push(setTimeout(() => this._scrollToEnd(true, true), 300));
        } else {
          // Normal scroll for other platforms
          this._scrollToEnd(true, true);
        }
        
        // Re-focus our message input and remove the dummy input
        if (messageInput && hadFocus) {
          if (dummyInput) {
            // On iOS, refocus real input before removing dummy
            messageInput.focus();
            
            // Small delay before removing dummy to ensure real input has focus
            setTimeout(() => {
              document.body.removeChild(dummyInput);
            }, 50);
          } else {
            // Non-iOS direct refocus
            messageInput.focus();
          }
        }
      } catch (error) {
        console.error('Error sending message:', error);
        toastService.error('Failed to send message. Please try again.');
        
        // Restore the message text if sending failed
        this.newMessage = messageText;
        this.isEphemeral = wasEphemeral;
        
        // Ensure focus is maintained even after an error
        if (messageInput && hadFocus) {
          messageInput.focus();
        }
      } finally {
        this.sendingMessage = false;
      }
    },

    // Store unread messages to be marked as read later when window gets focus
    storePendingReadMessages() {
      // Find all unread messages from the selected user 
      const unreadMessages = this.messages.filter(m => 
        m.sender_id === this.selectedUserId && 
        m.recipient_id === this.currentUserId && 
        !m.is_read
      );
      
      if (unreadMessages.length > 0) {
        // Store the IDs of messages that need to be marked as read
        this.pendingReadMessages = [
          ...this.pendingReadMessages,
          ...unreadMessages.map(m => m.id)
        ];
      }
    },

    // Handle visibility change events
    handleVisibilityChange() {
      this.isWindowFocused = document.visibilityState === 'visible';
      
      // When window becomes visible, mark pending messages as read
      if (this.isWindowFocused && this.pendingReadMessages.length > 0 && this.selectedUserId) {
        this.markMessagesAsRead();
      }
    },

    async markMessagesAsRead() {
      // Only proceed if window is focused
      if (!this.isWindowFocused) {
        this.storePendingReadMessages();
        return;
      }
      
      // Mark all unread messages from the selected user as read
      const unreadMessages = this.messages.filter(m => 
        m.sender_id === this.selectedUserId && 
        m.recipient_id === this.currentUserId && 
        !m.is_read
      );
      
      // Also include any pending messages that were stored while window was not focused
      const pendingMessageIds = new Set(this.pendingReadMessages);
      const pendingMessages = this.messages.filter(m => pendingMessageIds.has(m.id));
      
      // Combine both lists without duplicates
      const messagesToMark = [...new Set([...unreadMessages, ...pendingMessages])];
      
      if (messagesToMark.length === 0) {
        return; // No unread messages to mark
      }
      
      try {
        // Create an array to track all mark-as-read operations
        const markOperations = [];
        
        // First update local state before API call to make UI responsive
        for (const message of messagesToMark) {
          // Update the message in our local array
          const index = this.messages.findIndex(m => m.id === message.id);
          if (index !== -1) {
            // Create a new object to ensure Vue detects the change
            const updatedMessage = {...this.messages[index], is_read: true};
            this.messages.splice(index, 1, updatedMessage);
          }
          
          // Queue the API call
          markOperations.push(apiService.markMessageAsRead(message.id));
          
          // Don't remove ephemeral messages until page reload
          // They're deleted on the server but remain visible in the UI
        }
        
        // Update conversation unread count immediately for better UX
        const conversation = this.conversations.find(c => c.user.id === this.selectedUserId);
        if (conversation) {
          // Set unread count to 0 since we've read all messages from this user
          conversation.unread_count = 0;
        }
        
        // Clear the pending messages list
        this.pendingReadMessages = [];
        
        // Now wait for all API operations to complete in the background
        Promise.all(markOperations)
          .catch(error => {
            console.error('Some messages failed to mark as read:', error);
          });
      } catch (error) {
        console.error('Error marking messages as read:', error);
      }
    },

    // --- Decryption & Local Storage ---
    
    storeSentMessage(messageId, messageText) {
      try {
        // Get existing sent messages from localStorage
        const sentMessagesJson = localStorage.getItem(LOCAL_STORAGE_SENT_MESSAGES_KEY) || '{}';
        const sentMessages = JSON.parse(sentMessagesJson);
        
        // Add new message
        sentMessages[messageId] = {
          text: messageText,
          timestamp: new Date().toISOString(),
          recipientId: this.selectedUserId
        };
        
        // Store back in localStorage
        localStorage.setItem(LOCAL_STORAGE_SENT_MESSAGES_KEY, JSON.stringify(sentMessages));
      } catch (error) {
        console.error('Error storing sent message locally:', error);
      }
    },
    
    getSentMessage(messageId) {
      try {
        const sentMessagesJson = localStorage.getItem(LOCAL_STORAGE_SENT_MESSAGES_KEY) || '{}';
        const sentMessages = JSON.parse(sentMessagesJson);
        
        return sentMessages[messageId]?.text || null;
      } catch (error) {
        console.error('Error retrieving sent message from localStorage:', error);
        return null;
      }
    },
    
    async decryptMessages() {
      for (const message of this.messages) {
        await this.decryptMessage(message);
      }
    },
    
    async decryptMessage(message) {
      // Skip if already decrypted or not for us
      if (
        this.decryptedMessages[message.id] || 
        (message.sender_id !== this.currentUserId && message.recipient_id !== this.currentUserId)
      ) {
        return;
      }
      
      try {
        // Only decrypt messages sent to us
        if (message.recipient_id === this.currentUserId) {
          // Parse the encrypted content
          const encryptedData = JSON.parse(message.encrypted_content);
          
          // Verify signature for messages we receive
          if (message.signature) {
            // Mark as pending verification
            this.signatureVerifications = {
              ...this.signatureVerifications,
              [message.id]: 'pending'
            };
            
            try {
              // Get the sender's public key
              const keyResponse = await apiService.getUserPublicKey(message.sender_id);
              const senderPublicKey = keyResponse.public_key;
              
              // Verify the signature
              const isValid = await cryptoService.verifySignature(
                message.signature,
                encryptedData.message, // The encrypted message that was signed
                senderPublicKey
              );
              
              // Update verification status
              this.signatureVerifications = {
                ...this.signatureVerifications,
                [message.id]: isValid
              };
              
              // If invalid, add to the list of invalid signatures
              if (!isValid) {
                const index = this.invalidSignatureMessages.indexOf(message.id);
                if (index === -1) {
                  this.invalidSignatureMessages = [...this.invalidSignatureMessages, message.id];
                }
              }
            } catch (error) {
              console.error(`Error verifying signature for message ${message.id}:`, error);
              this.signatureVerifications = {
                ...this.signatureVerifications,
                [message.id]: false
              };
              const index = this.invalidSignatureMessages.indexOf(message.id);
              if (index === -1) {
                this.invalidSignatureMessages = [...this.invalidSignatureMessages, message.id];
              }
            }
          }
          
          // Decrypt the message (even if signature is invalid, but we'll show a warning)
          const decryptedMessage = await cryptoService.decryptMessage(
            encryptedData.message,
            encryptedData.key,
            encryptedData.iv,
            window.cryptoContext.privateKey
          );
          
          // Store the decrypted message
          this.decryptedMessages[message.id] = decryptedMessage;
        } else {
          // For messages we sent, check if we have the plaintext stored locally first
          const storedMessage = this.getSentMessage(message.id);
          
          // Verify signature for messages we sent too
          if (message.signature) {
            // Mark as pending verification
            this.signatureVerifications = {
              ...this.signatureVerifications,
              [message.id]: 'pending'
            };
            
            try {
              // Parse the encrypted content
              const encryptedData = JSON.parse(message.encrypted_content);
              
              // For messages we sent, we need to verify with our own public key
              // This gives users confidence that their messages are correctly signed
              const keyResponse = await apiService.checkKeyPairStatus();
              const myPublicKey = keyResponse.public_key;
              
              // Verify the signature
              const isValid = await cryptoService.verifySignature(
                message.signature,
                encryptedData.message,
                myPublicKey
              );
              
              // Update verification status
              this.signatureVerifications = {
                ...this.signatureVerifications,
                [message.id]: isValid
              };
              
              // If invalid, add to the list of invalid signatures
              if (!isValid) {
                const index = this.invalidSignatureMessages.indexOf(message.id);
                if (index === -1) {
                  this.invalidSignatureMessages = [...this.invalidSignatureMessages, message.id];
                }
              }
            } catch (error) {
              console.error(`Error verifying signature for sent message ${message.id}:`, error);
              // Don't mark our own messages as invalid - just leave the status as pending
              // since it's likely a technical issue rather than tampering
              this.signatureVerifications = {
                ...this.signatureVerifications,
                [message.id]: 'pending'
              };
            }
          }
          
          if (storedMessage) {
            // Use the locally stored message content
            this.decryptedMessages[message.id] = storedMessage;
          } else {
            // No local copy found, show the fallback message
            this.decryptedMessages[message.id] = FALLBACK_SENT_MESSAGE_TEXT;
          }
        }
      } catch (error) {
        console.error(`Error decrypting message ${message.id}:`, error);
        this.decryptedMessages[message.id] = DECRYPTION_FAILED_TEXT;
      }
    },

    // --- Polling ---
    
    startMessagePolling() {
      if (this.pollingActive) return;
      
      this.pollingActive = true;
      this.pollForMessages();
    },
    
    stopMessagePolling() {
      this.pollingActive = false;
      if (this.pollingTimeout) {
        clearTimeout(this.pollingTimeout);
      }
    },
    
    async pollForMessages() {
      if (!this.pollingActive) return;
      
      try {
        const response = await apiService.pollMessages(this.lastMessageId);
        
        if (response.messages && response.messages.length > 0) {
          // Update lastMessageId
          this.lastMessageId = Math.max(this.lastMessageId, ...response.messages.map(m => m.id));
          
          let shouldPlayNotification = false;
          let hasNewConversation = false;
          let newMessagesForCurrentConversation = false;
          
          // Process new messages
          for (const message of response.messages) {
            // If we're viewing the conversation with this user, add the message
            if (
              (message.sender_id === this.selectedUserId && message.recipient_id === this.currentUserId) ||
              (message.sender_id === this.currentUserId && message.recipient_id === this.selectedUserId)
            ) {
              // Add to current conversation if not already there
              if (!this.messages.find(m => m.id === message.id)) {
                this.messages.push(message);
                this.decryptMessage(message);
                newMessagesForCurrentConversation = true;
                
                // Mark as read immediately if we're the recipient, viewing this conversation, and window is focused
                if (message.recipient_id === this.currentUserId && !message.is_read && this.isWindowFocused) {
                  // Update local state first
                  message.is_read = true;
                  
                  // Then send API request
                  apiService.markMessageAsRead(message.id)
                    .catch(err => console.error(`Failed to mark message ${message.id} as read:`, err));
                  
                  // Don't remove ephemeral messages - they'll be deleted server-side but remain in UI
                } else if (message.recipient_id === this.currentUserId && !message.is_read) {
                  // Window not focused, add to pending messages
                  this.pendingReadMessages.push(message.id);
                }
              }
            } else {
              // If we're not viewing the conversation but we're the recipient, update unread counts
              if (message.recipient_id === this.currentUserId && !message.is_read) {
                // Find the conversation
                const conversation = this.conversations.find(c => c.user.id === message.sender_id);
                
                if (conversation) {
                  // Update unread count
                  conversation.unread_count = (conversation.unread_count || 0) + 1;
                  
                  // Set flag to play notification sound
                  shouldPlayNotification = true;
                } else {
                  // This is a new conversation - need to get the user info
                  hasNewConversation = true;
                  shouldPlayNotification = true;
                }
              }
            }
            
            // Update conversations list with the latest message
            this.updateConversationWithLatestMessage(message);
          }
          
          // Trigger scrolling for new messages in current conversation
          if (newMessagesForCurrentConversation && this.autoScrollEnabled) {
            this.$nextTick(() => {
              setTimeout(() => this._scrollToEnd(true, false), SCROLL_DELAY_MS);
            });
          }
          
          // If we have brand new conversations, reload the conversation list
          if (hasNewConversation) {
            this.loadConversations();
          }
          
          // Play notification sound if there are new unread messages not in the current conversation
          if (shouldPlayNotification && this.notificationSound) {
            try {
              // Reset the audio to the beginning in case it was already playing
              this.notificationSound.currentTime = 0;
              await this.notificationSound.play();
            } catch (err) {
              console.error('Error playing notification sound:', err);
            }
          }
        }
      } catch (error) {
        console.error('Error polling for messages:', error);
        // If there's an error, wait before trying again
        this.pollingTimeout = setTimeout(() => {
          if (this.pollingActive) this.pollForMessages();
        }, MESSAGE_POLLING_ERROR_RETRY_MS); // Use constant
        return;
      }
      
      // Continue polling
      this.pollingTimeout = setTimeout(() => {
        if (this.pollingActive) this.pollForMessages();
      }, MESSAGE_POLLING_INTERVAL_MS); // Use constant
    },
    
    startTypingIndicatorPolling() {
      if (this.typingPollingActive) return;
      
      this.typingPollingActive = true;
      this.pollForTypingIndicators();
    },
    
    stopTypingIndicatorPolling() {
      this.typingPollingActive = false;
      if (this.typingPollingTimeout) {
        clearTimeout(this.typingPollingTimeout);
      }
    },
    
    async pollForTypingIndicators() {
      if (!this.typingPollingActive || !this.selectedUserId) return;
      
      try {
        const response = await apiService.getTypingIndicators();
        
        // Update typing status for the user we're chatting with
        if (response.typing_users) {
          this.typingUsers = response.typing_users;
        }
      } catch (error) {
        console.error('Error polling for typing indicators:', error);
      }
      
      // Poll every few seconds
      this.typingPollingTimeout = setTimeout(() => {
        if (this.typingPollingActive) this.pollForTypingIndicators();
      }, TYPING_POLLING_INTERVAL_MS); // Use constant
    },

    // --- UI, Scrolling & Event Handlers ---

    handleVisualViewportResize() {
      if (!window.visualViewport) return;
      
      const offsetTop = window.visualViewport.offsetTop;
      const visibleHeight = window.visualViewport.height;
      const keyboardHeight = window.innerHeight - visibleHeight - offsetTop;
      const keyboardIsOpen = keyboardHeight > KEYBOARD_OPEN_THRESHOLD_PX; // Use constant
      
      // Don't continually update if height hasn't changed significantly
      if (Math.abs((this._lastKeyboardHeight || 0) - keyboardHeight) < 10) {
        return;
      }
      
      if (this._keyboardTimeout) clearTimeout(this._keyboardTimeout);
      
      this._lastKeyboardHeight = keyboardHeight;
      
      // Adjust header position to stay at the top of the visible area
      const header = document.querySelector('.conversation-header');
      if (header) {
        // For iOS, we need to reset the header position when keyboard closes
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        if (isIOS) {
          if (keyboardIsOpen) {
            // When keyboard is open, position header at viewport offsetTop
            header.style.top = `${offsetTop}px`;
            header.style.position = 'fixed';
          } else {
            // When keyboard is closed, reset header position to 0
            this._keyboardTimeout = setTimeout(() => {
              header.style.top = '0px';
              header.style.position = 'fixed';
            }, 300);
          }
        } else {
          // Non-iOS devices
          header.style.top = `${offsetTop}px`;
        }
      }
      
      // Adjust message container's sizing to account for keyboard
      const messageContainer = this.$refs.messageList;
      const inputContainer = document.querySelector('.message-input-container');
      
      if (inputContainer) {
        // Position the input just above the keyboard
        inputContainer.style.bottom = `${keyboardHeight < 0 ? 0 : keyboardHeight}px`;
        inputContainer.style.position = 'fixed';
      }
      
      if (messageContainer) {
        // Adjust bottom padding of message container based on input height and keyboard presence
        const inputHeight = inputContainer ? inputContainer.offsetHeight : 65;
        messageContainer.style.paddingBottom = `${inputHeight + 20}px`;
        
        // Track keyboard state for scrolling adjustments
        if (this._keyboardOpenState !== keyboardIsOpen) {
          this._keyboardOpenState = keyboardIsOpen;
          
          // Delayed scroll after keyboard state change
          this._keyboardTimeout = setTimeout(() => { // Use a moderate delay (e.g., 300ms)
            if (messageContainer && this.messages.length > 0) {
                this._scrollToEnd(true, false); // Force scroll
            }
          }, 300);
        }
      }
    },
    
    updateLayoutForKeyboard() {
      // Adjust UI based on keyboard presence
      const header = document.querySelector('.conversation-header');
      const messageContainer = this.$refs.messageList;
      const inputContainer = document.querySelector('.message-input-container');
      
      if (!header || !inputContainer) return;
      
      // Get visual viewport data
      const viewport = window.visualViewport || { offsetTop: 0, height: window.innerHeight };
      const offsetTop = viewport.offsetTop;
      
      // Adjust header positioning
      header.style.top = `${offsetTop}px`;
      header.style.position = 'fixed';
      
      // Calculate effective keyboard height (includes safe area on iOS)
      const effectiveKeyboardHeight = this.keyboardHeight + (this.keyboardHeight > 0 ? this.safeAreaBottom : 0);
      
      // Position input container above keyboard
      inputContainer.style.bottom = `${effectiveKeyboardHeight}px`;
      inputContainer.style.position = 'fixed';
      
      // Adjust message container
      if (messageContainer) {
        // Calculate input height
        const inputHeight = inputContainer.offsetHeight || 65;
        
        // Set padding to ensure content is not hidden under input
        const bottomPadding = inputHeight + 20;
        messageContainer.style.paddingBottom = `${bottomPadding}px`;
        
        // Adjust top position based on header
        messageContainer.style.top = `${offsetTop + header.offsetHeight}px`;
        messageContainer.style.height = `calc(100% - ${offsetTop + header.offsetHeight}px)`;
      }
    },

    // Improved scrolling that works better on iOS
    _scrollToEnd(force = false, isSentMessage = false) {
      if (!this.$refs.messageList) return;
      if (!force && this.userHasScrolled) return;

      const messageList = this.$refs.messageList;
      this._clearScrollTimeouts();

      this.$nextTick(() => {
        if (!this.$refs.messageList) return;

        const messages = messageList.querySelectorAll('.message-bubble');
        const lastMessage = messages[messages.length - 1];

        if (lastMessage) {
          // iOS-specific scrolling
          if (this.isIOS) {
            // Delayed approach for iOS
            const scrollOptions = { behavior: 'auto' };
            lastMessage.scrollIntoView(scrollOptions);
            
            // Additional scrolls to ensure it sticks
            setTimeout(() => {
              // Force to absolute bottom with extra padding for iOS keyboard
              const paddingForKeyboard = isSentMessage ? SCROLL_PADDING_SENT_PX : SCROLL_PADDING_BASE_PX;
              messageList.scrollTop = messageList.scrollHeight + paddingForKeyboard;
            }, 50);
          } else {
            // Non-iOS devices
            lastMessage.scrollIntoView({ block: 'end', behavior: 'auto' });
            messageList.scrollTop = messageList.scrollHeight;
          }
        } else {
          // Fallback for no messages
          messageList.scrollTop = messageList.scrollHeight;
        }

        // Reset scroll tracking
        this.userHasScrolled = false;
        this.autoScrollEnabled = true;
      });
    },

    _clearScrollTimeouts() {
       if (this._scrollTimeouts) {
        this._scrollTimeouts.forEach(timeout => clearTimeout(timeout));
      }
      this._scrollTimeouts = [];
    },

    // Handle scroll events to determine if user has manually scrolled up
    handleScroll() {
      if (!this.$refs.messageList) return;
      
      const messageList = this.$refs.messageList;
      const { scrollTop, scrollHeight, clientHeight } = messageList;
      
      // Calculate distance from bottom
      const distanceFromBottom = scrollHeight - (scrollTop + clientHeight);
      
      // Check against threshold to determine if user scrolled up
      const userScrolledUp = distanceFromBottom > SCROLL_THRESHOLD_RE_ENABLE_AUTO; // Use constant
      
      if (userScrolledUp && this.autoScrollEnabled) {
        this.userHasScrolled = true;
        this.autoScrollEnabled = false;
      } else if (!userScrolledUp && !this.autoScrollEnabled) {
         // If user scrolls back near the bottom, re-enable auto-scroll
        this.userHasScrolled = false;
        this.autoScrollEnabled = true;
      }
    },

    // --- Typing Indicators ---

    handleTyping() {
      // Auto-resize the textarea first
      this.autoResizeTextarea();
      
      // Only trigger typing events when in an active conversation
      if (!this.selectedUserId) return;
      
      // If we weren't typing before, send typing indicator
      if (!this.isTyping) {
        this.isTyping = true;
        this.sendTypingStatus(true);
      }
      
      // Clear any existing timeout
      if (this.typingTimeout) {
        clearTimeout(this.typingTimeout);
      }
      
      // Set timeout to detect when user stops typing
      this.typingTimeout = setTimeout(() => {
        if (this.isTyping) {
          this.isTyping = false;
          this.sendTypingStatus(false);
        }
      }, this.typingDebounceDelay);
    },
    
    async sendTypingStatus(isTyping) {
      if (!this.selectedUserId) return;
      
      try {
        await apiService.sendTypingIndicator(this.selectedUserId, isTyping);
      } catch (error) {
        console.error('Error sending typing indicator:', error);
      }
    },

    // --- Utility Methods ---

    formatTimestamp(timestamp) {
      if (!timestamp) return '';
      
      const date = new Date(timestamp);
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    },
    
    formatConversationTime(timestamp) {
      if (!timestamp) return '';
      
      const date = new Date(timestamp);
      const now = new Date();
      const yesterday = new Date(now);
      yesterday.setDate(now.getDate() - 1);
      
      // If today, show time
      if (date.toDateString() === now.toDateString()) {
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      }
      // If yesterday, show "Yesterday"
      else if (date.toDateString() === yesterday.toDateString()) {
        return 'Yesterday';
      }
      // Otherwise show date
      else {
        return date.toLocaleDateString([], { month: 'short', day: 'numeric' });
      }
    },
    
    filterUsers() {
      const query = this.searchQuery.toLowerCase();
      this.filteredUsers = this.users.filter(user => user.username.toLowerCase().includes(query));
    },
    
    // Search for a user by exact username match
    async searchUser() {
      const query = this.searchQuery.trim();
      if (!query) {
        this.filteredUsers = [];
        this.searchSubmitted = false;
        return;
      }
      
      try {
        this.loadingUsers = true;
        this.searchSubmitted = true;
        const response = await apiService.searchUserByExactUsername(query);
        this.filteredUsers = response.users || [];
      } catch (error) {
        console.error('Error searching for user:', error);
        this.filteredUsers = [];
      } finally {
        this.loadingUsers = false;
      }
    },

    // --- Navigation ---
    backToConversations() {
      const conversationContainer = document.querySelector('.conversation-layout');
      if (conversationContainer) {
        // Reset any transform before transitioning
        conversationContainer.style.transform = 'translateX(0)';
        conversationContainer.style.opacity = '1';
      }
      
      this.selectedUserId = null;
      this.stopTypingIndicatorPolling();
      
      // Reset swipe state
      this.isSwiping = false;
      this.swipeProgress = 0;
    },

    // --- Textarea Auto-Resize ---

    autoResizeTextarea() {
      const textarea = this.$refs.messageInput;
      if (!textarea) return;
      
      // Reset height to auto first to get the correct scrollHeight
      textarea.style.height = 'auto';
      
      // Get the computed font size and line height
      const style = window.getComputedStyle(textarea);
      const lineHeight = parseFloat(style.lineHeight) || 18;
      
      // Calculate minimum height of one line plus padding
      const paddingTop = parseFloat(style.paddingTop) || 0;
      const paddingBottom = parseFloat(style.paddingBottom) || 0;
      const minHeight = lineHeight + paddingTop + paddingBottom;
      
      // Set the height to scrollHeight capped by the max height
      const desiredHeight = Math.max(textarea.scrollHeight, minHeight);
      const newHeight = Math.min(desiredHeight, TEXTAREA_MAX_HEIGHT_PX);
      textarea.style.height = `${newHeight}px`;
      
      // For iOS, we may need to trigger layout recalculation
      if (this.isIOS) {
        // Force layout recalculation after height change
        this.updateLayoutForKeyboard();
      }
    },

    // --- iOS-specific Methods ---
    
    updateSafeAreaInsets() {
      // Get the safe area insets
      if (this.isIOS) {
        // Get CSS environment variables for safe areas
        const safeAreaBottom = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--sat-bottom') || '0');
        this.safeAreaBottom = isNaN(safeAreaBottom) ? 0 : safeAreaBottom;
      }
    },
    
    preventIOSElasticScrolling() {
      if (!this.isIOS) return;
      
      // Prevent elastic scrolling/bouncing on iOS
      document.body.addEventListener('touchmove', (e) => {
        if (e.target.closest('.scrollable')) {
          const scrollEl = e.target.closest('.scrollable');
          const scrollTop = scrollEl.scrollTop;
          const scrollHeight = scrollEl.scrollHeight;
          const clientHeight = scrollEl.clientHeight;
          
          // If at top and trying to scroll up, or at bottom and trying to scroll down
          if ((scrollTop <= 0 && e.touches[0].clientY > 0) || 
              (scrollTop + clientHeight >= scrollHeight && e.touches[0].clientY < 0)) {
            e.preventDefault();
          }
        } else if (!e.target.closest('input, textarea')) {
          // Prevent scrolling on non-scrollable elements
          e.preventDefault();
        }
      }, { passive: false });
    },
    
    handleIOSFocusIn(event) {
      // Handle iOS focus in event
      if (event.target.tagName === 'TEXTAREA' || event.target.tagName === 'INPUT') {
        // Mark as fullscreen to prevent unwanted UI movements
        this.fullscreenActive = true;
        
        // iOS needs extra time to adjust for keyboard
        setTimeout(() => {
          if (this.selectedUserId) {
            this._scrollToEnd(true, false);
          }
        }, 600);
      }
    },
    
    handleIOSFocusOut(event) {
      // Handle iOS focus out event
      if (event.target.tagName === 'TEXTAREA' || event.target.tagName === 'INPUT') {
        // Reset fullscreen status
        this.fullscreenActive = false;
        
        // Delay to ensure UI has fully adjusted after keyboard dismissal
        setTimeout(() => {
          // Ensure proper positioning of elements
          this.handleVisualViewportResize();
        }, 300);
      }
    },
    
    handleWindowResize() {
      // Handle window resize, particularly orientation changes
      this.viewportHeight = window.innerHeight;
      this.updateSafeAreaInsets();
      
      // Force redraw layout
      setTimeout(() => {
        this.handleVisualViewportResize();
        
        // Adjust message container
        if (this.selectedUserId) {
          this._scrollToEnd(true, false);
        }
      }, 100);
    },

    // Improved scrolling implementation
    scrollToBottom(force = false, isSentMessage = false) {
      if (!this.$refs.messageList) return;
      if (!force && this.userHasScrolled) return;

      // Clear any existing scroll timeout
      if (this.scrollTimeout) {
        clearTimeout(this.scrollTimeout);
      }

      // Use nextTick to ensure DOM is updated
      this.$nextTick(() => {
        const messageList = this.$refs.messageList;
        const header = document.querySelector('.conversation-header');
        const inputContainer = document.querySelector('.message-input-container');
        const messages = messageList.querySelectorAll('.message-bubble');
        const lastMessage = messages[messages.length - 1];

        if (lastMessage) {
          // Get the heights of all relevant elements
          const headerHeight = header ? header.offsetHeight : 0;
          const inputHeight = inputContainer ? inputContainer.offsetHeight : 0;
          const keyboardHeight = window.innerHeight - (window.visualViewport?.height || window.innerHeight);
          
          // Calculate the actual visible area for messages
          const totalBottomSpace = inputHeight + keyboardHeight;
          const visibleHeight = window.innerHeight - headerHeight - totalBottomSpace;
          
          // Calculate the padding needed
          const padding = isSentMessage ? SCROLL_PADDING_SENT_PX : SCROLL_PADDING_BASE_PX;
          
          // Calculate the scroll position to ensure last message is fully visible
          const scrollPosition = messageList.scrollHeight - visibleHeight + padding;

          // Scroll to the calculated position
          messageList.scrollTo({
            top: scrollPosition,
            behavior: 'auto'
          });

          // For early messages, do an additional check after layout stabilizes
          if (messages.length <= 9) {
            this.scrollTimeout = setTimeout(() => {
              // Recalculate after layout stabilizes
              const newVisibleHeight = window.innerHeight - headerHeight - totalBottomSpace;
              const newScrollPosition = messageList.scrollHeight - newVisibleHeight + padding;
              
              messageList.scrollTo({
                top: newScrollPosition,
                behavior: 'auto'
              });
            }, 100);
          }

          // Reset scroll tracking
          this.userHasScrolled = false;
          this.autoScrollEnabled = true;
        }
      });
    },

    // Handle keyboard changes
    handleKeyboardChange() {
      if (this.keyboardTimeout) {
        clearTimeout(this.keyboardTimeout);
      }

      // Wait for keyboard animation and layout to stabilize
      this.keyboardTimeout = setTimeout(() => {
        // Force a re-layout to ensure dimensions are correct
        this.$nextTick(() => {
          if (this.autoScrollEnabled) {
            this.scrollToBottom(true, false);
          }
        });
      }, KEYBOARD_SCROLL_DELAY_MS);
    },

    // Update handleVisualViewportResize to properly handle the visible area
    handleVisualViewportResize() {
      if (!window.visualViewport) return;
      
      const offsetTop = window.visualViewport.offsetTop;
      const visibleHeight = window.visualViewport.height;
      const keyboardHeight = window.innerHeight - visibleHeight - offsetTop;
      const keyboardIsOpen = keyboardHeight > KEYBOARD_OPEN_THRESHOLD_PX;
      
      // Don't continually update if height hasn't changed significantly
      if (Math.abs((this._lastKeyboardHeight || 0) - keyboardHeight) < 10) {
        return;
      }
      
      this._lastKeyboardHeight = keyboardHeight;
      
      // Adjust header position
      const header = document.querySelector('.conversation-header');
      if (header) {
        header.style.top = `${offsetTop}px`;
        header.style.position = 'fixed';
      }
      
      // Adjust message container's sizing
      const messageContainer = this.$refs.messageList;
      const inputContainer = document.querySelector('.message-input-container');
      
      if (inputContainer) {
        // Position input container above keyboard
        inputContainer.style.bottom = `${keyboardHeight < 0 ? 0 : keyboardHeight}px`;
        inputContainer.style.position = 'fixed';
      }
      
      if (messageContainer) {
        // Get the input container height
        const inputHeight = inputContainer ? inputContainer.offsetHeight : 65;
        const headerHeight = header ? header.offsetHeight : 0;
        
        // Calculate the actual visible area
        const totalBottomSpace = inputHeight + keyboardHeight;
        const visibleHeight = window.innerHeight - headerHeight - totalBottomSpace;
        
        // Set the message container height to match the visible area
        messageContainer.style.height = `${visibleHeight}px`;
        messageContainer.style.top = `${headerHeight}px`;
        
        if (this._keyboardOpenState !== keyboardIsOpen) {
          this._keyboardOpenState = keyboardIsOpen;
          this.handleKeyboardChange();
        }
      }
    },

    handleAfterLeave() {
      // Reset any keyboard-related styles after the transition
      const header = document.querySelector('.conversation-header');
      const inputContainer = document.querySelector('.message-input-container');
      
      if (header) {
        header.style.top = '0';
        header.style.position = 'fixed';
      }
      
      if (inputContainer) {
        inputContainer.style.bottom = '0';
        inputContainer.style.position = 'fixed';
      }
    },
    
    // Add these new methods for swipe handling
    handleTouchStart(e) {
      if (!this.selectedUserId) return;
      
      // Store initial touch state
      this.touchState = {
        isActive: true,
        startTime: Date.now(),
        startX: e.touches[0].clientX,
        startY: e.touches[0].clientY,
        lastX: e.touches[0].clientX,
        lastY: e.touches[0].clientY
      };
      
      this.isSwiping = true;
      this.swipeProgress = 0;
      
      // Prevent event propagation if not on the conversation container
      if (!e.target.closest('.conversation-layout')) {
        e.stopPropagation();
      }
    },
    
    handleTouchMove(e) {
      if (!this.touchState.isActive || !this.isSwiping) return;
      
      const currentX = e.touches[0].clientX;
      const currentY = e.touches[0].clientY;
      
      // Update last position
      this.touchState.lastX = currentX;
      this.touchState.lastY = currentY;
      
      // Calculate movement
      const deltaX = currentX - this.touchState.startX;
      const deltaY = Math.abs(currentY - this.touchState.startY);
      
      // Only handle swipe if it's on the conversation container
      const isOnConversation = e.target.closest('.conversation-layout');
      if (!isOnConversation) {
        e.stopPropagation();
        return;
      }
      
      // Only allow right swipe with minimal vertical movement
      if (deltaY > this.swipeMaxY || deltaX < 0) {
        this.resetSwipe();
        return;
      }
      
      // Calculate swipe progress
      this.swipeProgress = Math.min(deltaX / window.innerWidth, 1);
      
      // Apply transform
      const conversationContainer = document.querySelector('.conversation-layout');
      if (conversationContainer) {
        const translateX = Math.min(deltaX, window.innerWidth);
        conversationContainer.style.transform = `translateX(${translateX}px)`;
        conversationContainer.style.opacity = `${1 - this.swipeProgress}`;
      }
    },
    
    handleTouchEnd(e) {
      if (!this.touchState.isActive || !this.isSwiping) return;
      
      const deltaX = this.touchState.lastX - this.touchState.startX;
      const deltaTime = Date.now() - this.touchState.startTime;
      const velocity = deltaX / deltaTime;
      
      // Only handle swipe completion if it's on the conversation container
      const isOnConversation = e.target.closest('.conversation-layout');
      if (!isOnConversation) {
        e.stopPropagation();
        return;
      }
      
      // If swipe distance exceeds threshold or has sufficient velocity, trigger back action
      if (deltaX > this.swipeThreshold || (velocity > this.velocityThreshold && this.swipeProgress > 0.3)) {
        this.backToConversations();
      } else {
        this.resetSwipe();
      }
      
      this.isSwiping = false;
      this.touchState.isActive = false;
    },
 
    resetSwipe() {
      const conversationContainer = document.querySelector('.conversation-layout');
      if (conversationContainer) {
        conversationContainer.style.transform = 'translateX(0)';
        conversationContainer.style.opacity = '1';
      }
      this.swipeProgress = 0;
      this.isSwiping = false;
      this.touchState.isActive = false;
    },
    
    // Add these new methods for iOS handling
    handleIOSKeyboard() {
      if (!this.isIOS) return;
      
      // Get the visual viewport
      const viewport = window.visualViewport;
      if (!viewport) return;
      
      // Calculate keyboard height
      const keyboardHeight = window.innerHeight - viewport.height;
      const isKeyboardOpen = keyboardHeight > 50;
      
      // Update our keyboard state
      this.keyboardHeight = keyboardHeight;
      
      // Adjust layout based on keyboard state
      this.$nextTick(() => {
        // Get the header and input container
        const header = document.querySelector('.conversation-header');
        const inputContainer = document.querySelector('.message-input-container');
        const messageContainer = this.$refs.messageList;
        
        if (header) {
          // Position header at the top of the visible area
          header.style.top = `${viewport.offsetTop}px`;
          header.style.position = 'fixed';
        }
        
        if (inputContainer) {
          // Position input container above keyboard
          inputContainer.style.bottom = `${keyboardHeight}px`;
          inputContainer.style.position = 'fixed';
        }
        
        if (messageContainer) {
          // Calculate the visible area for messages
          const headerHeight = header ? header.offsetHeight : 0;
          const inputHeight = inputContainer ? inputContainer.offsetHeight : 0;
          const visibleHeight = viewport.height - headerHeight - inputHeight;
          
          // Set message container height and position
          messageContainer.style.height = `${visibleHeight}px`;
          messageContainer.style.top = `${headerHeight + viewport.offsetTop}px`;
          
          // Scroll to bottom when keyboard opens
          if (isKeyboardOpen && this.autoScrollEnabled) {
            setTimeout(() => {
              this.scrollToBottom(true, false);
            }, 300);
          }
        }
      });
    },
    
    // Add these new methods for swipe-to-delete
    handleConversationTouchStart(e, conversationId) {
      // Only allow swipe if we're not already in a conversation
      if (this.selectedUserId) return;
      
      this.swipeDeleteState.startX = e.touches[0].clientX;
      this.swipeDeleteState.currentX = this.swipeDeleteState.startX;
      this.swipeDeleteState.activeId = conversationId;
      this.swipeDeleteState.isSwiping = true;
      
      // Prevent event propagation
      e.stopPropagation();
    },
    
    handleConversationTouchMove(e) {
      if (!this.swipeDeleteState.isSwiping) return;
      
      this.swipeDeleteState.currentX = e.touches[0].clientX;
      const deltaX = this.swipeDeleteState.currentX - this.swipeDeleteState.startX;
      
      // Only allow left swipe (negative deltaX)
      if (deltaX < 0) {
        const conversationElement = document.querySelector(`[data-conversation-id="${this.swipeDeleteState.activeId}"]`);
        const deleteButton = conversationElement?.parentElement?.querySelector('.delete-button-container');
        
        if (conversationElement && deleteButton) {
          // Limit the swipe distance
          const maxSwipe = -80; // Maximum swipe distance in pixels
          const translateX = Math.max(deltaX, maxSwipe);
          conversationElement.style.transform = `translateX(${translateX}px)`;
          
          // Show delete button when swiped
          if (deltaX < -this.swipeDeleteState.threshold) {
            deleteButton.style.right = '0';
          } else {
            deleteButton.style.right = '-80px';
          }
        }
      }
      
      // Prevent event propagation
      e.stopPropagation();
    },
    
    handleConversationTouchEnd() {
      if (!this.swipeDeleteState.isSwiping) return;
      
      const conversationElement = document.querySelector(`[data-conversation-id="${this.swipeDeleteState.activeId}"]`);
      const deleteButton = conversationElement?.parentElement?.querySelector('.delete-button-container');
      
      if (conversationElement && deleteButton) {
        const deltaX = this.swipeDeleteState.currentX - this.swipeDeleteState.startX;
        
        // If swipe distance exceeds threshold, show delete button
        if (deltaX < -this.swipeDeleteState.threshold) {
          conversationElement.style.transform = 'translateX(-80px)';
          deleteButton.style.right = '0';
        } else {
          // Reset position if swipe wasn't far enough
          conversationElement.style.transform = 'translateX(0)';
          deleteButton.style.right = '-80px';
        }
      }
      
      this.swipeDeleteState.isSwiping = false;
    },
    
    handleDeleteClick(conversationId, event) {
      if (event) {
        event.preventDefault();
        event.stopPropagation();
      }
      
      // Store the conversation ID for deletion
      this.deletionTargetId = conversationId;
      this.showDeleteConfirmModal = true;
      
      // Reset the swipe position
      const conversationElement = document.querySelector(`[data-conversation-id="${conversationId}"]`);
      const deleteButton = conversationElement?.parentElement?.querySelector('.delete-button-container');
      
      if (conversationElement && deleteButton) {
        conversationElement.style.transform = 'translateX(0)';
        deleteButton.style.right = '-80px';
      }
    },
    
    resetSwipePosition(conversationId) {
      const conversationElement = document.querySelector(`[data-conversation-id="${conversationId}"]`);
      const deleteButton = conversationElement?.parentElement?.querySelector('.delete-button-container');
      
      if (conversationElement && deleteButton) {
        conversationElement.style.transform = 'translateX(0)';
        deleteButton.style.right = '-80px';
      }
    },
    
    // Add new method for handling delete from conversation view
    handleConversationDelete() {
      this.deletionTargetId = this.selectedUserId;
      this.showDeleteConfirmModal = true;
    },
    
    showMessageContextMenu(message) {
      this.selectedMessage = message;
      this.showMessageContextModal = true;
    },
    
    async deleteMessage(message) {
      if (!message) return;
      
      try {
        // Call API to delete the message
        await apiService.deleteMessage(message.id);
        
        // Remove the message from the UI
        const messageIndex = this.messages.findIndex(m => m.id === message.id);
        if (messageIndex !== -1) {
          this.messages.splice(messageIndex, 1);
        }
        
        // Remove from decrypted messages if present
        if (this.decryptedMessages[message.id]) {
          delete this.decryptedMessages[message.id];
        }
        
        // Update conversation latest message if needed
        const conversation = this.conversations.find(c => 
          c.user.id === message.sender_id || c.user.id === message.recipient_id
        );
        
        if (conversation && conversation.latest_message && conversation.latest_message.id === message.id) {
          // Find the next latest message
          const otherMessages = this.messages.filter(m => 
            (m.sender_id === conversation.user.id || m.recipient_id === conversation.user.id) &&
            m.id !== message.id
          );
          
          if (otherMessages.length > 0) {
            conversation.latest_message = otherMessages[otherMessages.length - 1];
          } else {
            conversation.latest_message = null;
          }
        }
        
        // Close the context menu
        this.showMessageContextModal = false;
        this.selectedMessage = null;
        
        toastService.success('Message deleted successfully');
      } catch (error) {
        console.error('Error deleting message:', error);
        toastService.error('Failed to delete message. Please try again.');
      }
    },
    
    handleMessageTouchStart(event, message) {
      // Prevent default behavior including text selection
      event.preventDefault();
      
      // Store the initial touch position
      this.longPressStartPosition = {
        x: event.touches[0].clientX,
        y: event.touches[0].clientY
      };
      
      // Clear any existing timeout
      if (this.longPressTimeout) {
        clearTimeout(this.longPressTimeout);
      }
      
      // Set timeout for long press
      this.longPressTimeout = setTimeout(() => {
        // Only trigger if we haven't moved too far from initial position
        if (this.longPressStartPosition) {
          this.showMessageContextMenu(message);
          
          // Add haptic feedback if available
          if (window.navigator && window.navigator.vibrate) {
            window.navigator.vibrate(50);
          }
        }
      }, this.longPressDelay);
    },
    
    handleMessageTouchMove(event) {
      // Prevent default behavior
      event.preventDefault();
      
      if (!this.longPressStartPosition) return;
      
      const currentX = event.touches[0].clientX;
      const currentY = event.touches[0].clientY;
      
      // Calculate distance moved
      const deltaX = Math.abs(currentX - this.longPressStartPosition.x);
      const deltaY = Math.abs(currentY - this.longPressStartPosition.y);
      
      // If moved more than 10px in any direction, cancel long press
      if (deltaX > 10 || deltaY > 10) {
        this.cancelLongPress();
      }
    },
    
    handleMessageTouchEnd(event) {
      // Prevent default behavior
      event.preventDefault();
      this.cancelLongPress();
    },
    
    cancelLongPress() {
      if (this.longPressTimeout) {
        clearTimeout(this.longPressTimeout);
        this.longPressTimeout = null;
      }
      this.longPressStartPosition = null;
    },
    
    closeMessageContextMenu() {
      this.showMessageContextModal = false;
      this.selectedMessage = null;
    },
    
    async copyMessage(message) {
      if (!message || !this.decryptedMessages[message.id]) return;
      
      try {
        await navigator.clipboard.writeText(this.decryptedMessages[message.id]);
        this.closeMessageContextMenu();
        toastService.success('Message copied to clipboard');
      } catch (error) {
        console.error('Error copying message:', error);
        toastService.error('Failed to copy message');
      }
    },
    
    showMessageInfo(message) {
      this.selectedMessage = message;
      this.showMessageContextModal = false;
      this.showMessageInfoModal = true;
    },
    
    closeMessageInfoModal() {
      this.showMessageInfoModal = false;
      this.selectedMessage = null;
    },
    
    formatDetailedTimestamp(timestamp) {
      if (!timestamp) return '';
      
      const date = new Date(timestamp);
      return date.toLocaleString(undefined, {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      });
    },
  }
  };
</script>

<style scoped>
/* Improved typing indicator styles */
.typing-dot {
  display: inline-block;
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background-color: #9CA3AF;
  margin-right: 2px;
  opacity: 0.8;
  animation: typing-animation 1.4s infinite ease-in-out;
}

.typing-dot:nth-child(1) {
  animation-delay: 0s;
}

.typing-dot:nth-child(2) {
  animation-delay: 0.2s;
}

.typing-dot:nth-child(3) {
  animation-delay: 0.4s;
  margin-right: 0;
}

@keyframes typing-animation {
  0%, 60%, 100% {
    transform: translateY(0);
    opacity: 0.6;
  }
  30% {
    transform: translateY(-3px);
    opacity: 1;
  }
}

/* Message bubble styles for better word wrapping */
.message-bubble {
  overflow-wrap: break-word;
  word-wrap: break-word;
  word-break: break-word;
  hyphens: auto;
  max-width: 100%;
  animation: message-appear 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  transform-origin: center;
  will-change: transform, opacity;
  backface-visibility: hidden;
  perspective: 1000;
}

/* Enhanced message appearance animation */
@keyframes message-appear {
  0% {
    opacity: 0;
    transform: scale(0.8) translateY(10px);
  }
  50% {
    opacity: 0.5;
    transform: scale(1.05) translateY(-2px);
  }
  100% {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

/* Send button loading animation */
.send-button-loading {
  position: relative;
  overflow: hidden;
}

.send-button-loading::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.2),
    transparent
  );
  animation: send-button-shine 1.5s infinite;
}

@keyframes send-button-shine {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}

/* Message input container animation */
.message-input-container {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 50;
  width: 100%;
  background-color: #1f2937;
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  transform: translateY(0);
}

.message-input-container.sending {
  transform: translateY(-2px);
}

/* Ephemeral message indicator animation */
.ephemeral-indicator {
  animation: ephemeral-pulse 2s infinite;
  position: relative;
  overflow: hidden;
}

.ephemeral-indicator::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.2),
    transparent
  );
  animation: ephemeral-shine 2s infinite;
}

@keyframes ephemeral-pulse {
  0% {
    opacity: 0.6;
    transform: scale(1);
  }
  50% {
    opacity: 1;
    transform: scale(1.05);
  }
  100% {
    opacity: 0.6;
    transform: scale(1);
  }
}

@keyframes ephemeral-shine {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}

/* Message timestamp animation */
.message-timestamp {
  transition: all 0.3s ease;
  opacity: 0.7;
  transform: translateY(0);
}

.message-bubble:hover .message-timestamp {
  opacity: 1;
  transform: translateY(-2px);
}

/* Add these new styles for the send button */
.send-button {
  position: relative;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  transform-origin: center;
  background-color: #2563eb !important; /* Default blue color */
  -webkit-tap-highlight-color: transparent; /* Remove tap highlight */
}

.send-button:not(:disabled):hover {
  transform: scale(1.1) rotate(5deg);
  background-color: #3b82f6 !important; /* Lighter blue on hover */
}

.send-button:not(:disabled):active {
  transform: scale(0.95);
  background-color: #1d4ed8 !important; /* Darker blue when active */
}

.send-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.send-button .send-icon {
  transition: transform 0.3s ease;
}

.send-button:not(:disabled):hover .send-icon {
  transform: translateX(2px);
}

.send-button.send-button-loading {
  background-color: #1e40af !important; /* Darker blue when sending */
}

/* Add these new styles for the message input */
.message-input {
  transition: all 0.3s ease;
  transform-origin: bottom;
}

.message-input:focus {
  transform: translateY(-1px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Add these new styles for the ephemeral toggle button */
.ephemeral-toggle {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  transform-origin: center;
  -webkit-tap-highlight-color: transparent; /* Remove tap highlight on mobile */
}

.ephemeral-toggle:hover {
  transform: scale(1.1) rotate(15deg);
}

.ephemeral-toggle.active {
  animation: ephemeral-toggle-active 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  background-color: #d97706 !important; /* Ensure yellow-600 color sticks */
}

.ephemeral-toggle.active:hover {
  background-color: #b45309 !important; /* Darker yellow on hover when active */
}

@keyframes ephemeral-toggle-active {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.2) rotate(180deg);
  }
  100% {
    transform: scale(1) rotate(360deg);
  }
}

/* Loading spinner animation */
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Modal transition animations */
.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

.modal-enter-to,
.modal-leave-from {
  opacity: 1;
  transform: scale(1);
}

/* Conversation list item animation */
.conversation-item {
  transition: all 0.2s ease;
}

.conversation-item:hover {
  transform: translateX(4px);
}

/* Button hover animations */
.button-hover {
  transition: all 0.2s ease;
}

.button-hover:hover {
  transform: scale(1.05);
}

/* Message input focus animation */
.message-input:focus {
  transition: all 0.2s ease;
  transform: translateY(-1px);
}

/* Make sure scrollable areas still function properly */
.scrollable {
  -webkit-overflow-scrolling: touch; /* Adds momentum scrolling on iOS */
}

/* Container layout for proper fixed positioning */
.conversation-layout {
  position: relative;
  display: flex;
  flex-direction: column;
  height: 100vh;
  width: 100%;
  background-color: #111827; /* bg-gray-900 */
  transition: transform 0.2s ease-out, opacity 0.2s ease-out;
  will-change: transform, opacity;
  touch-action: pan-y pinch-zoom;
  -webkit-overflow-scrolling: touch;
  overscroll-behavior: none;
}

/* Fixed header at top of screen */
.conversation-header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 50;
  width: 100%;
  transition: transform 0.3s ease;
}

/* Message container constrained between header and input */
.messages-container {
  position: absolute;
  top: 57px; /* Height of the header */
  bottom: 65px; /* Height of the input container */
  left: 0;
  right: 0;
  overflow-y: auto;
  padding-bottom: 70px; /* Initial padding to avoid content being hidden behind input */
}

/* Fixed message input container at bottom */
.message-input-container {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 50;
  width: 100%;
  background-color: #1f2937; /* bg-gray-800 */
  transition: transform 0.3s ease;
}

/* iOS-specific adjustments */
@supports (-webkit-touch-callout: none) {
  .conversation-header,
  .message-input-container {
    position: fixed;
  }
  
  /* Ensure the header resets properly when keyboard closes */
  .conversation-header {
    transition: top 0.3s ease;
    will-change: top;
    z-index: 50;
  }
  
  .messages-container {
    -webkit-overflow-scrolling: touch;
  }
}

/* Fix for iOS and Android devices to prevent unwanted scrolling */
html, body {
  height: 100%;
  width: 100%;
  overflow: hidden;
  -webkit-overflow-scrolling: touch;
  overscroll-behavior: none;
}

/* Fix the visual positioning on iOS */
.messages-container {
  /* Use relative instead of fixed position for iOS compatibility */
  position: absolute;
  padding-bottom: max(70px, env(safe-area-inset-bottom));
}

/* Fix for conversation header on iOS */
.conversation-layout {
  isolation: isolate; /* Create stacking context */
}

/* New message button animation */
.new-message-button {
  transition: all 0.3s ease;
}

.new-message-button:hover {
  transform: rotate(90deg);
}

/* Settings button animation */
.settings-button {
  transition: all 0.3s ease;
}

.settings-button:hover {
  transform: rotate(180deg);
}

/* Back button animation */
.back-button {
  transition: all 0.2s ease;
}

.back-button:hover {
  transform: translateX(-4px);
}

/* Delete button animation */
.delete-button {
  transition: all 0.2s ease;
}

.delete-button:hover {
  transform: scale(1.1);
  color: #EF4444; /* text-red-500 */
}

/* Ephemeral message indicator animation */
.ephemeral-indicator {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    opacity: 0.6;
  }
  50% {
    opacity: 1;
  }
  100% {
    opacity: 0.6;
  }
}

/* Message timestamp fade animation */
.message-timestamp {
  transition: opacity 0.3s ease;
}

.message-bubble:hover .message-timestamp {
  opacity: 1;
}

/* Unread badge animation */
.unread-badge {
  animation: badge-pulse 2s infinite;
}

@keyframes badge-pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}

/* Search input focus animation */
.search-input:focus {
  transition: all 0.3s ease;
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* User search result hover animation */
.user-result {
  transition: all 0.2s ease;
}

.user-result:hover {
  transform: translateX(8px);
  background-color: #374151; /* bg-gray-700 */
}

/* Loading skeleton animation */
.skeleton {
  animation: skeleton-loading 1s linear infinite alternate;
}

@keyframes skeleton-loading {
  0% {
    background-color: #374151; /* bg-gray-700 */
  }
  100% {
    background-color: #4B5563; /* bg-gray-600 */
  }
}

/* Add these new animation styles at the top of the style section */
/* Conversation slide transitions */
.conversation-slide-enter-active,
.conversation-slide-leave-active {
  transition: all 0.3s ease;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 100;
  height: 100vh;
  height: -webkit-fill-available;
  overflow: hidden;
  transform-origin: right;
  will-change: transform, opacity;
  backface-visibility: hidden;
  perspective: 1000;
}

.conversation-slide-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.conversation-slide-enter-to {
  opacity: 1;
  transform: translateX(0);
}

.conversation-slide-leave-from {
  opacity: 1;
  transform: translateX(0);
}

.conversation-slide-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

/* Ensure the conversation layout takes full height during transition */
.conversation-layout {
  position: relative;
  display: flex;
  flex-direction: column;
  height: 100vh;
  height: -webkit-fill-available;
  width: 100%;
  background-color: #111827; /* bg-gray-900 */
  overflow: hidden;
  transform-origin: right;
  will-change: transform, opacity;
  backface-visibility: hidden;
  perspective: 1000;
}

/* Adjust the messages container to work with the transition */
.messages-container {
  position: absolute;
  top: 57px;
  bottom: 65px;
  left: 0;
  right: 0;
  overflow-y: auto;
  padding-bottom: 70px;
  height: calc(100vh - 122px); /* Full height minus header and input */
  height: calc(-webkit-fill-available - 122px);
  -webkit-overflow-scrolling: touch;
  transform: translateZ(0);
  backface-visibility: hidden;
  perspective: 1000;
}

/* Add these iOS-specific styles */
@supports (-webkit-touch-callout: none) {
  /* Prevent elastic scrolling */
  .conversation-layout {
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: none;
    height: 100vh;
    height: -webkit-fill-available;
  }
  
  /* Fix for fixed positioning */
  .conversation-header,
  .message-input-container {
    position: fixed;
    z-index: 50;
    transform: translateZ(0);
    backface-visibility: hidden;
    perspective: 1000;
  }
  
  /* Ensure proper viewport height */
  .conversation-layout {
    height: 100vh;
    height: -webkit-fill-available;
  }
  
  /* Fix for safe area insets */
  .message-input-container {
    padding-bottom: env(safe-area-inset-bottom);
  }
  
  /* Improve scrolling performance */
  .messages-container {
    -webkit-overflow-scrolling: touch;
    transform: translateZ(0);
    backface-visibility: hidden;
    perspective: 1000;
  }
  
  /* Prevent unwanted zooming */
  input, textarea {
    font-size: 16px;
  }
  
  /* Fix for keyboard appearance */
  .message-input-container {
    transition: transform 0.3s ease;
  }
  
  /* Ensure proper stacking context */
  .conversation-layout {
    isolation: isolate;
  }
}

/* Add these new styles for swipe-to-delete */
.delete-button-container {
  width: 80px;
  right: -80px;
  transition: right 0.2s ease-out;
  z-index: 10;
  cursor: pointer;
  border: none;
  outline: none;
  -webkit-tap-highlight-color: transparent;
  pointer-events: auto;
}

[data-conversation-id] {
  touch-action: pan-y pinch-zoom;
  position: relative;
  z-index: 20;
  pointer-events: auto;
}

/* Ensure the conversation item has proper stacking context */
.relative {
  isolation: isolate;
  pointer-events: auto;
}

/* Add smooth transition for the swipe animation */
.w-full.transition-transform {
  will-change: transform;
  backface-visibility: hidden;
  perspective: 1000;
}

/* Add these new styles to prevent text selection during long press */
.message-bubble {
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  touch-action: none;
}

/* Allow text selection in the context menu */
.modal-enter-active {
  -webkit-touch-callout: default;
  -webkit-user-select: text;
  -khtml-user-select: text;
  -moz-user-select: text;
  -ms-user-select: text;
  user-select: text;
  touch-action: auto;
}

/* Make message content selectable when not in long-press mode */
.message-bubble .whitespace-pre-wrap {
  -webkit-touch-callout: text;
  -webkit-user-select: text;
  -khtml-user-select: text;
  -moz-user-select: text;
  -ms-user-select: text;
  user-select: text;
  touch-action: auto;
}
</style>