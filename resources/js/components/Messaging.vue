/**
 * Messaging.vue - End-to-end encrypted messaging component
 */
<template>
  <div class="h-full flex flex-col bg-gray-900">
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
          Please go to the <a href="/settings" class="text-blue-400 hover:text-blue-300">settings</a> to set up your keys.
        </p>
        <a href="/settings" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          Go to Settings
        </a>
      </div>
    </div>
    
    <!-- Messaging interface -->
    <div v-else class="h-full flex flex-col">
      <div class="flex-1 flex overflow-hidden">
        <!-- Conversation list -->
        <div 
          class="bg-gray-800 border-r border-gray-700 flex flex-col"
          :class="[
            isMobile ? (conversationListVisible ? 'block w-full' : 'hidden') : 'block w-64'
          ]"
        >
          <div class="p-3 border-b border-gray-700">
            <button
              @click="showNewMessageModal = true"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              New Message
            </button>
          </div>
          
          <div class="flex-1 overflow-y-auto">
            <div v-if="conversations.length === 0" class="p-4 text-center text-gray-400 text-sm">
              No conversations yet.
            </div>
            
            <div v-else>
              <button
                v-for="conversation in conversations"
                :key="conversation.user.id"
                @click="selectConversation(conversation.user.id)"
                class="w-full text-left p-3 border-b border-gray-700 hover:bg-gray-700 focus:outline-none focus:bg-gray-700 conversation-item"
                :class="{ 'bg-gray-700': selectedUserId === conversation.user.id }"
              >
                <div class="flex items-start">
                  <!-- User avatar/initial -->
                  <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-medium text-lg mr-3 flex-shrink-0">
                    {{ conversation.user.username.charAt(0).toUpperCase() }}
                  </div>
                  
                  <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-center mb-1">
                      <span class="font-medium text-white truncate">{{ conversation.user.username }}</span>
                      <span v-if="conversation.unread_count > 0" class="bg-blue-500 h-2.5 w-2.5 rounded-full"></span>
                    </div>
                    <p v-if="conversation.latest_message" class="text-gray-400 text-xs truncate">
                      <span v-if="conversation.latest_message.sender_id === currentUserId">You: </span>
                      <span v-if="decryptedMessages[conversation.latest_message.id]">
                        {{ decryptedMessages[conversation.latest_message.id] }}
                      </span>
                      <span v-else>
                        [Encrypted message]
                      </span>
                    </p>
                  </div>
                </div>
              </button>
            </div>
          </div>
        </div>
        
        <!-- Message area -->
        <div class="flex-1 flex flex-col bg-gray-900 w-full overflow-hidden">
          <div v-if="!selectedUserId" class="h-full flex items-center justify-center text-gray-500">
            <div class="text-center">
              <svg class="h-12 w-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
              </svg>
              <p>Select a conversation or start a new message</p>
            </div>
          </div>
          
          <div v-else class="h-full flex flex-col">
            <!-- Conversation header -->
            <div class="bg-gray-800 border-b border-gray-700 p-3 flex justify-between items-center">
              <div class="flex items-center">
                <!-- Back button (mobile only) -->
                <button 
                  v-if="isMobile" 
                  @click="showConversations" 
                  class="p-2 mr-2 text-gray-400 hover:text-white focus:outline-none focus:text-white"
                >
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                </button>
                
                <!-- User avatar -->
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-medium text-lg mr-3">
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
              
              <div class="flex items-center">
                <!-- Delete conversation button -->
                <button
                  @click="showDeleteConfirmModal = true"
                  class="p-1 text-gray-400 hover:text-red-500 focus:outline-none mx-2"
                  title="Delete conversation"
                >
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
                
                <!-- Close conversation button (desktop only) -->
                <button
                  v-if="!isMobile"
                  @click="selectedUserId = null"
                  class="p-1 text-gray-400 hover:text-white focus:outline-none focus:text-white"
                >
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
            </div>
            
            <!-- Message list -->
            <div ref="messageList" class="flex-1 overflow-y-auto p-4 space-y-3 overflow-x-hidden w-full max-w-full" @scroll="handleScroll">
              <div v-if="loadingMessages" class="flex justify-center py-4">
                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
              
              <div v-else-if="messages.length === 0" class="text-center text-gray-500 py-4">
                No messages yet. Send the first message!
              </div>
              
              <div 
                v-else 
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
                  
                  <!-- Message context menu -->
                  <div class="flex justify-between items-center mt-1">
                    <!-- Timestamp -->
                    <div class="text-xs opacity-70 message-timestamp">
                      {{ formatTimestamp(message.created_at) }}
                    </div>
                    
                    <!-- Context menu button -->
                    <button
                      @click.stop="showMessageContextMenu(message)"
                      class="p-1 text-gray-300 hover:text-white focus:outline-none"
                      aria-label="Message options"
                    >
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            
            <!-- Message input -->
            <div class="bg-gray-800 border-t border-gray-700 p-3">
              <form @submit.prevent="sendMessage" class="flex flex-col space-y-2">
                <div class="flex space-x-3">
                  <textarea
                    ref="messageInput"
                    v-model="newMessage"
                    @keydown.enter.prevent="sendMessage"
                    @input="handleTyping"
                    placeholder="Type a secure message..."
                    class="flex-1 bg-gray-700 border border-gray-600 rounded-md p-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    :disabled="sendingMessage"
                    rows="1"
                  ></textarea>
                  
                  <!-- Ephemeral message button -->
                  <button 
                    type="button"
                    @click="isEphemeral = !isEphemeral"
                    class="px-3 py-2 text-white rounded-md flex-shrink-0 ephemeral-toggle"
                    :class="isEphemeral ? 'bg-yellow-600 hover:bg-yellow-500 active' : 'bg-gray-700 hover:bg-gray-600'"
                    aria-label="Toggle ephemeral message"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </button>
                  
                  <button
                    type="submit"
                    :disabled="sendingMessage || !newMessage.trim()"
                    class="px-4 py-2 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 send-button"
                    :class="{ 
                      'bg-blue-600 hover:bg-blue-500': !sendingMessage,
                      'bg-blue-800 hover:bg-blue-700': sendingMessage
                    }"
                  >
                    <svg v-if="sendingMessage" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                      <path fill-rule="evenodd" d="M12 2a1 1 0 0 1 .932.638l7 18a1 1 0 0 1-1.326 1.281L13 19.517V13a1 1 0 1 0-2 0v6.517l-5.606 2.402a1 1 0 0 1-1.326-1.281l7-18A1 1 0 0 1 12 2Z" clip-rule="evenodd"/>
                    </svg>
                  </button>
                </div>
                
                <!-- Ephemeral message info text -->
                <div v-if="isEphemeral" class="flex items-center">
                  <span class="text-xs text-gray-400">
                    This message will disappear after being read
                  </span>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- New message modal -->
    <div v-if="showNewMessageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4 modal-enter-active">
      <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md">
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
          <form @submit.prevent="searchUser" class="flex space-x-2" autocomplete="off">
            <!-- Hidden field to trick browser autofill -->
            <input type="text" style="display:none" name="username" autocomplete="username">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Enter exact username..."
              autocomplete="off"
              :readonly="autoFillWorkaround"
              @focus="handleInputFocus"
              class="block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm bg-gray-900 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
        
        <div v-else-if="searchSubmitted && filteredUsers.length === 0" class="text-center text-gray-400 py-4">
          No users found
        </div>
        
        <div v-else-if="filteredUsers.length > 0" class="max-h-60 overflow-y-auto border border-gray-700 rounded-md">
          <button
            v-for="user in filteredUsers"
            :key="user.id"
            @click="startConversation(user)"
            class="w-full text-left p-3 border-b border-gray-700 hover:bg-gray-700 focus:outline-none focus:bg-gray-700 last:border-b-0 flex items-center"
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
    <div v-if="showDeleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4 modal-enter-active">
      <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium text-white mb-4">Delete Conversation</h3>
        <p class="text-gray-400 mb-6">Are you sure you want to delete this conversation?</p>
        <div class="flex justify-end space-x-3">
          <button
            @click="showDeleteConfirmModal = false"
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
    
    <!-- Message context menu modal -->
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
      loadingConversations: false,
      
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
      scrollThreshold: 100,
      
      // Typing indicators
      typingUsers: {},
      isTyping: false,
      typingTimeout: null,
      typingPollingActive: false,
      typingPollingTimeout: null,
      typingDebounceDelay: 1000,
      
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
      
      // Mobile UI state
      isMobile: false,
      conversationListVisible: true,
      
      // Autofill workaround
      autoFillWorkaround: false,
      
      // Message context menu state
      showMessageContextModal: false,
      selectedMessage: null,
      
      // Message info modal state
      showMessageInfoModal: false
    };
  },
  async mounted() {
    // Initialize notification sound
    this.notificationSound = new Audio('/notify.mp3');
    
    await this.initializeKeys();
    
    if (this.keysReady) {
      await this.loadConversations();
      this.startMessagePolling();
    }
    
    // Initialize mobile detection
    this.checkMobileScreen();
    window.addEventListener('resize', this.checkMobileScreen);
  },
  beforeUnmount() {
    this.stopMessagePolling();
    this.stopTypingIndicatorPolling();
    window.removeEventListener('resize', this.checkMobileScreen);
  },
  watch: {
    async selectedUserId(newVal) {
      if (newVal) {
        await this.loadMessages(newVal);
        this.startTypingIndicatorPolling();
        // On mobile, hide the conversation list when a conversation is selected
        if (this.isMobile) {
          this.conversationListVisible = false;
        }
      } else {
        this.stopTypingIndicatorPolling();
      }
    },
    
    messages: {
      handler() {
        this.$nextTick(() => {
          this.scrollToBottom();
          this.decryptMessages();
        });
      },
      deep: true
    },
    
    typingUsers: {
      handler() {
        if (this.autoScrollEnabled) {
          this.scrollToBottom();
        }
      },
      deep: true
    },
    
    decryptedMessages: {
      handler() {
        if (this.autoScrollEnabled) {
          this.scrollToBottom();
        }
      },
      deep: true
    },
    
    showNewMessageModal(isOpen) {
      if (isOpen) {
        // Clear previous search results
        this.searchQuery = '';
        this.filteredUsers = [];
        this.loadingUsers = false;
        this.searchSubmitted = false;
      }
    }
  },
  methods: {
    // Key initialization
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
            this.keyError = true;
          } else {
            this.keyError = true;
          }
        }
      } catch (error) {
        console.error('Error initializing keys:', error);
        this.keyError = true;
      }
    },
    
    // Conversation management
    async loadConversations() {
      if (this.loadingConversations) return;
      
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
    
    // Modified method to use server-side search
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
    
    selectConversation(userId) {
      this.selectedUserId = userId;
      
      // Find the user's name from the conversations list
      const conversation = this.conversations.find(c => c.user.id === userId);
      if (conversation) {
        this.selectedUserName = conversation.user.username;
      }
    },
    
    // Modified method to use server-side search
    async filterUsers() {
      await this.searchUser();
    },
    
    startConversation(user) {
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
    },
    
    // Message management
    async loadMessages(userId) {
      if (this.loadingMessages) return;
      
      try {
        this.loadingMessages = true;
        this.messages = [];
        this.decryptedMessages = {};
        
        const response = await apiService.getConversation(userId);
        this.messages = response.messages.reverse(); // Newest at the bottom
        
        if (this.messages.length > 0) {
          this.lastMessageId = Math.max(...this.messages.map(m => m.id));
        }
        
        // Decrypt the messages
        await this.decryptMessages();
        
        // Mark messages as read
        await this.markMessagesAsRead();
        
        // Update the conversation unread count in the sidebar
        const conversation = this.conversations.find(c => c.user.id === userId);
        if (conversation && conversation.unread_count > 0) {
          conversation.unread_count = 0;
          
          // Force Vue to recognize the change
          const index = this.conversations.findIndex(c => c.user.id === userId);
          if (index !== -1) {
            this.conversations[index] = {...conversation};
          }
        }
        
        // Scroll to bottom after messages are loaded
        this.$nextTick(() => {
          this.scrollToBottom();
          // Focus the message input
          if (this.$refs.messageInput) {
            this.$refs.messageInput.focus();
          }
        });
      } catch (error) {
        console.error('Error loading messages:', error);
      } finally {
        this.loadingMessages = false;
      }
    },
    
    async sendMessage() {
      if (!this.newMessage.trim() || !this.selectedUserId || this.sendingMessage) return;
      
      const messageText = this.newMessage.trim();
      const messageInput = this.$refs.messageInput;
      
      try {
        this.sendingMessage = true;
        
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
        
        // Send the encrypted message to the server
        const response = await apiService.sendMessage(
          this.selectedUserId,
          encryptedContent,
          encryptedData.signature,
          this.isEphemeral
        );
        
        // Add the sent message to the UI
        const newMessage = response.data;
        this.messages.push(newMessage);
        this.decryptedMessages[newMessage.id] = messageText;
        this.lastMessageId = newMessage.id;
        
        // Store the sent message locally
        this.storeSentMessage(newMessage.id, messageText);
        
        // Update the conversation list
        this.updateConversationWithLatestMessage(newMessage);
        
        // Clear the input
        this.newMessage = '';
        
        // Scroll to bottom
        this.scrollToBottom();
        
        // Re-focus the input field
        this.$nextTick(() => {
          if (messageInput) {
            messageInput.focus();
          }
        });
      } catch (error) {
        console.error('Error sending message:', error);
        toastService.error('Failed to send message. Please try again.');
      } finally {
        this.sendingMessage = false;
      }
    },
    
    // Store sent message in localStorage
    storeSentMessage(messageId, messageText) {
      try {
        const sentMessagesJson = localStorage.getItem('sentMessages') || '{}';
        const sentMessages = JSON.parse(sentMessagesJson);
        
        sentMessages[messageId] = {
          text: messageText,
          timestamp: new Date().toISOString(),
          recipientId: this.selectedUserId
        };
        
        localStorage.setItem('sentMessages', JSON.stringify(sentMessages));
      } catch (error) {
        console.error('Error storing sent message locally:', error);
      }
    },
    
    // Retrieve sent message from localStorage
    getSentMessage(messageId) {
      try {
        const sentMessagesJson = localStorage.getItem('sentMessages') || '{}';
        const sentMessages = JSON.parse(sentMessagesJson);
        
        return sentMessages[messageId]?.text || null;
      } catch (error) {
        console.error('Error retrieving sent message from localStorage:', error);
        return null;
      }
    },
    
    async decryptMessages() {
      const promises = this.messages.map(message => this.decryptMessage(message));
      await Promise.all(promises);
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
                encryptedData.message,
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
          
          // Store the decrypted message - Vue 3 compatible
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
            this.decryptedMessages[message.id] = storedMessage;
          } else {
            this.decryptedMessages[message.id] = "Sent message (not stored locally)";
          }
        }
      } catch (error) {
        console.error(`Error decrypting message ${message.id}:`, error);
        this.decryptedMessages[message.id] = "[Decryption failed]";
      }
    },
    
    async markMessagesAsRead() {
      // Mark all unread messages from the selected user as read
      const unreadMessages = this.messages.filter(m => 
        m.sender_id === this.selectedUserId && 
        m.recipient_id === this.currentUserId && 
        !m.is_read
      );
      
      if (unreadMessages.length === 0) return;
      
      try {
        // Create an array to track all mark-as-read operations
        const markOperations = [];
        
        // First update local state before API call to make UI responsive
        for (const message of unreadMessages) {
          // Update the message in our local array
          const index = this.messages.findIndex(m => m.id === message.id);
          if (index !== -1) {
            // Create a new object and update the array element
            const updatedMessage = {...this.messages[index], is_read: true};
            this.messages[index] = updatedMessage;
          }
          
          // Queue the API call
          markOperations.push(apiService.markMessageAsRead(message.id));
          
          // For ephemeral messages, remove them from the UI after a short delay
          if (message.is_ephemeral) {
            setTimeout(() => {
              const currentIndex = this.messages.findIndex(m => m.id === message.id);
              if (currentIndex !== -1) {
                this.messages.splice(currentIndex, 1);
              }
              
              // Also remove from decrypted messages
              if (this.decryptedMessages[message.id]) {
                delete this.decryptedMessages[message.id];
              }
            }, 500);
          }
        }
        
        // Update conversation unread count immediately for better UX
        const conversation = this.conversations.find(c => c.user.id === this.selectedUserId);
        if (conversation) {
          conversation.unread_count = 0;
          
          const index = this.conversations.findIndex(c => c.user.id === this.selectedUserId);
          if (index !== -1) {
            // Update with a new object to ensure reactivity
            this.conversations[index] = {...conversation};
          }
        }
        
        // Wait for all API operations to complete in the background
        Promise.all(markOperations).catch(error => {
          console.error('Some messages failed to mark as read:', error);
        });
      } catch (error) {
        console.error('Error marking messages as read:', error);
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
          return;
        }
      }
      
      // Update the latest message
      conversation.latest_message = message;
        
      // Handle unread count
      if (message.recipient_id === this.currentUserId && !message.is_read && this.selectedUserId !== message.sender_id) {
        conversation.unread_count = (conversation.unread_count || 0) + 1;
      }
      
      // If this message was just marked as read, recalculate unread count
      if (message.is_read && message.recipient_id === this.currentUserId) {
        const unreadCount = this.messages.filter(m => 
          m.sender_id === otherUserId && 
          m.recipient_id === this.currentUserId && 
          !m.is_read
        ).length;
        
        conversation.unread_count = unreadCount;
      }
      
      // Move this conversation to the top (most recent)
      const index = this.conversations.findIndex(c => c.user.id === otherUserId);
      if (index > 0) {
        this.conversations.unshift(this.conversations.splice(index, 1)[0]);
      } else if (index === -1) {
        this.conversations.unshift(conversation);
      }
    },
    
    // Message polling
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
                
                // Mark as read immediately if we're the recipient and viewing this conversation
                if (message.recipient_id === this.currentUserId && !message.is_read) {
                  // Update local state first
                  message.is_read = true;
                  
                  // Then send API request
                  apiService.markMessageAsRead(message.id)
                    .catch(err => console.error(`Failed to mark message ${message.id} as read:`, err));
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
          
          // If we have brand new conversations, reload the conversation list
          if (hasNewConversation) {
            this.loadConversations();
          }
          
          // Play notification sound if there are new unread messages not in the current conversation
          if (shouldPlayNotification && this.notificationSound) {
            try {
              this.notificationSound.currentTime = 0;
              await this.notificationSound.play();
            } catch (err) {
              console.error('Error playing notification sound:', err);
            }
          }
        }
      } catch (error) {
        console.error('Error polling for messages:', error);
        this.pollingTimeout = setTimeout(() => {
          if (this.pollingActive) this.pollForMessages();
        }, 5000);
        return;
      }
      
      // Continue polling
      if (this.pollingActive) {
        this.pollForMessages();
      }
    },
    
    // UI helpers
    scrollToBottom() {
      if (!this.$refs.messageList || !this.autoScrollEnabled) return;
      
      this.$nextTick(() => {
        const el = this.$refs.messageList;
        el.scrollTop = el.scrollHeight;
      });
    },
    
    // Handle scroll events to determine if user has manually scrolled up
    handleScroll() {
      if (!this.$refs.messageList) return;
      
      const { scrollTop, scrollHeight, clientHeight } = this.$refs.messageList;
      const distanceFromBottom = scrollHeight - scrollTop - clientHeight;
      
      this.autoScrollEnabled = distanceFromBottom <= this.scrollThreshold;
    },
    
    formatTimestamp(timestamp) {
      const date = new Date(timestamp);
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    },
    
    // Mobile responsiveness methods
    checkMobileScreen() {
      this.isMobile = window.innerWidth < 768; // Standard md breakpoint
      
      // On mobile, default to showing conversation list until a conversation is selected
      if (this.isMobile) {
        this.conversationListVisible = !this.selectedUserId;
      } else {
        this.conversationListVisible = true;
      }
    },
    
    showConversations() {
      this.conversationListVisible = true;
      if (this.isMobile) {
        this.selectedUserId = null;
      }
    },
    
    // Typing indicators
    handleTyping() {
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
      
      // Poll every 2-3 seconds
      this.typingPollingTimeout = setTimeout(() => {
        if (this.typingPollingActive) this.pollForTypingIndicators();
      }, 2000);
    },
    
    async deleteConversation() {
      try {
        // Call API to delete the conversation
        await apiService.deleteConversation(this.selectedUserId);
        
        // Remove the conversation from the list
        this.conversations = this.conversations.filter(c => c.user.id !== this.selectedUserId);
        
        // Clear the selected user
        this.selectedUserId = null;
        
        // On mobile, show the conversation list
        if (this.isMobile) {
          this.conversationListVisible = true;
        }
      } catch (error) {
        console.error('Error deleting conversation:', error);
        toastService.error('Error deleting conversation. Please try again.');
      } finally {
        // Close the confirmation modal
        this.showDeleteConfirmModal = false;
      }
    },
    
    // Autofill workaround
    handleInputFocus() {
      // Set readonly to prevent autofill when focusing
      this.autoFillWorkaround = true;
      // Remove readonly attribute after a short delay
      setTimeout(() => {
        this.autoFillWorkaround = false;
      }, 100);
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
    }
  }
};
</script>

<style scoped>
.typing-dot {
  display: inline-block;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #6b7280;
  margin-right: 3px;
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
}

/* Message bubble styles with improved word wrapping */
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

/* Message timestamp animation */
.message-timestamp {
  transition: opacity 0.3s ease;
  opacity: 0.7;
}

.message-bubble:hover .message-timestamp {
  opacity: 1;
}

/* Ephemeral message indicator animation */
.ephemeral-indicator {
  animation: ephemeral-subtle-pulse 3s ease-in-out infinite;
  position: relative;
  overflow: hidden;
}

/* Ephemeral toggle button styles */
.ephemeral-toggle {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  transform-origin: center;
  -webkit-tap-highlight-color: transparent; /* Remove tap highlight */
}

.ephemeral-toggle:hover {
  transform: scale(1.05);
}

.ephemeral-toggle.active {
  background-color: #d97706 !important; /* Ensure yellow-600 color sticks */
}

.ephemeral-toggle.active:hover {
  background-color: #b45309 !important; /* Darker yellow on hover when active */
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
    rgba(255, 255, 255, 0.1),
    transparent
  );
  animation: ephemeral-subtle-shine 4s ease-in-out infinite;
}

@keyframes ephemeral-subtle-pulse {
  0%, 100% {
    opacity: 0.85;
    transform: scale(1);
  }
  50% {
    opacity: 1;
    transform: scale(1.02);
  }
}

@keyframes ephemeral-subtle-shine {
  0% {
    transform: translateX(-200%);
  }
  40%, 100% {
    transform: translateX(200%);
  }
}

/* Conversation list item animations */
.conversation-item {
  transition: all 0.2s ease;
  transform-origin: left;
}

.conversation-item:hover {
  transform: translateX(4px);
}

/* Modal animations */
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

/* Message list animations */
.message-list-enter-active,
.message-list-leave-active {
  transition: all 0.3s ease;
}

.message-list-enter-from,
.message-list-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

.message-list-enter-to,
.message-list-leave-from {
  opacity: 1;
  transform: translateY(0);
}

/* Loading spinner animation */
.loading-spinner {
  animation: spin 1s linear infinite;
}

/* Hover effects for buttons */
.button-hover {
  transition: all 0.2s ease;
}

.button-hover:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

@keyframes typing-animation {
  0%, 60%, 100% {
    transform: translateY(0);
    opacity: 0.6;
  }
  30% {
    transform: translateY(-4px);
    opacity: 1;
  }
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Add a new style for the send button */
.send-button {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  transform-origin: center;
  -webkit-tap-highlight-color: transparent; /* Remove tap highlight */
  background-color: #2563eb !important; /* Default blue color */
}

.send-button:hover {
  transform: scale(1.05);
}

.send-button:active {
  background-color: #1d4ed8 !important; /* Active blue color */
  transform: scale(0.98);
}

/* Additional styles for the send button when in sending state */
.send-button.sending {
  background-color: #1e40af !important; /* Darker blue when sending */
}
</style>