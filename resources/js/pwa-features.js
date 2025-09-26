// PWA Online/Offline Detection
document.addEventListener('DOMContentLoaded', function() {
    // Check initial connection status
    updateConnectionStatus(navigator.onLine);
    
    // Listen for online event
    window.addEventListener('online', function() {
        updateConnectionStatus(true);
    });
    
    // Listen for offline event
    window.addEventListener('offline', function() {
        updateConnectionStatus(false);
    });
    
    // Function to update UI based on connection status
    function updateConnectionStatus(isOnline) {
        // Remove existing status indicator if it exists
        const existingIndicator = document.getElementById('connection-status-indicator');
        if (existingIndicator) {
            existingIndicator.remove();
        }
        
        // Create connection status indicator
        const indicator = document.createElement('div');
        indicator.id = 'connection-status-indicator';
        indicator.style.position = 'fixed';
        indicator.style.bottom = '20px';
        indicator.style.right = '20px';
        indicator.style.padding = '8px 16px';
        indicator.style.borderRadius = '4px';
        indicator.style.fontSize = '14px';
        indicator.style.zIndex = '9999';
        indicator.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        
        if (isOnline) {
            indicator.textContent = 'Online';
            indicator.style.backgroundColor = '#10b981'; // green
            indicator.style.color = 'white';
        } else {
            indicator.textContent = 'Offline';
            indicator.style.backgroundColor = '#ef4444'; // red
            indicator.style.color = 'white';
        }
        
        document.body.appendChild(indicator);
        
        // Auto-hide online indicator after 3 seconds, keep offline indicator visible
        if (isOnline) {
            setTimeout(function() {
                if (document.body.contains(indicator)) {
                    indicator.remove();
                }
            }, 3000);
        }
        
        // Update service worker when coming online
        if (isOnline && 'serviceWorker' in navigator) {
            navigator.serviceWorker.ready.then(function(registration) {
                if (registration.active) {
                    // Send message to service worker to sync any pending requests
                    registration.active.postMessage({ type: 'SYNC_ONLINE' });
                }
            });
        }
    }
    
    // Function to check if we're in offline mode based on service worker
    window.isOfflineMode = function() {
        return !navigator.onLine;
    };
    
    // Function to warn users about offline limitations
    window.showOfflineWarning = function(feature) {
        if (!navigator.onLine) {
            const message = `This feature (${feature}) requires an internet connection and may not work properly while offline.`;
            
            // Use SweetAlert2 if available, otherwise use alert
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Offline Mode',
                    text: message,
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            } else {
                alert(message);
            }
            return false; // Prevent the feature from executing
        }
        return true; // Allow the feature to proceed
    };
    
    // Intercept audio recording functionality 
    const originalMediaRecorder = window.MediaRecorder;
    if (originalMediaRecorder) {
        // We'll add a check before recording starts
        window.MediaRecorder = function(stream) {
            if (!navigator.onLine) {
                console.warn('Audio recording requires internet connection for upload');
            }
            return new originalMediaRecorder(stream);
        };
    }
    
    // Add offline checks to forms that require server interaction
    document.addEventListener('submit', function(e) {
        const formAction = e.target.action || '';
        
        // Check if this form requires online connection
        const requiresOnline = formAction.includes('/upload') || 
                              formAction.includes('/recordings') || 
                              formAction.includes('/quiz') || 
                              formAction.includes('/placement');
        
        if (requiresOnline && !navigator.onLine) {
            e.preventDefault();
            
            const feature = formAction.includes('/quiz') ? 'Quiz Submission' : 
                           formAction.includes('/placement') ? 'Placement Test' : 
                           formAction.includes('/recordings') ? 'Audio Recording' : 
                           'Form Submission';
            
            showOfflineWarning(feature);
        }
    });
    
    // Add offline checks to links that require online connection
    document.addEventListener('click', function(e) {
        if (e.target.tagName === 'A') {
            const href = e.target.getAttribute('href') || '';
            
            // Check if this link requires online connection
            const requiresOnline = href.includes('/admin') || 
                                  href.includes('/upload') || 
                                  href.includes('/recordings') ||
                                  href.includes('/quiz') ||
                                  href.includes('/placement');
            
            if (requiresOnline && !navigator.onLine) {
                e.preventDefault();
                const feature = href.includes('/quiz') ? 'Quiz' : 
                               href.includes('/placement') ? 'Placement Test' : 
                               href.includes('/recordings') ? 'Audio Recording' : 
                               href.includes('/admin') ? 'Admin Panel' : 
                               'Feature';
                
                showOfflineWarning(feature);
            }
        }
    });
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { showOfflineWarning: window.showOfflineWarning };
}