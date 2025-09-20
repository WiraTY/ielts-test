<div class="mt-4">
    <div class="flex items-center justify-between mb-2">
        <h3 class="text-lg font-medium">Speaking Practice</h3>
        <span class="text-sm text-gray-500">{{ $timeLeft }}s / {{ $duration }}s</span>
    </div>
    
    <!-- Recording Display (Existing or New) -->
    @if($hasNewRecording || $existingRecording)
        <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex justify-between items-center">
                <div>
                    @if($hasNewRecording)
                        <p class="text-sm font-medium text-blue-800">Recording saved successfully!</p>
                        <p class="text-xs text-blue-600">Recorded just now</p>
                    @else
                        <p class="text-sm font-medium text-blue-800">Your existing recording</p>
                        <p class="text-xs text-blue-600">Recorded on: {{ $existingRecording->created_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>
                <button wire:click="deleteRecording" 
                        class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600"
                        onclick="return confirm('Are you sure you want to delete this recording?')">
                    Delete
                </button>
            </div>
            <div class="mt-2 bg-gray-100 p-2 rounded">
                <audio class="w-full" controls>
                    <source src="{{ asset('storage/' . ($hasNewRecording ? $newRecordingPath : $existingRecording->file_path)) }}" type="audio/webm">
                    Your browser does not support the audio element.
                </audio>
            </div>
        </div>
    @endif
    
    <!-- Recording in progress -->
    @if($isRecording)
        <div class="mb-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
            <div class="flex items-center">
                <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse mr-2"></div>
                <span class="text-red-500 font-medium">Recording in progress...</span>
            </div>
        </div>
    @endif
    
    <!-- Temporary Preview (only shown after recording but before save confirmation) -->
    @if($showPreview)
        <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
            <p class="text-sm font-medium text-green-800 mb-2">Preview your recording</p>
            <div class="bg-gray-100 p-2 rounded">
                <audio id="previewAudio" class="w-full" controls></audio>
                <div class="flex space-x-2 mt-2">
                    <button id="previewPlayBtn" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                        Play
                    </button>
                    <button id="previewSaveBtn" class="px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600">
                        Save Recording
                    </button>
                </div>
            </div>
        </div>
    @endif
    
    <div class="flex items-center space-x-4">
        @if(!$isRecording)
            <button wire:click="startRecording" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600" id="startBtn" onclick="console.log('Start button clicked'); console.log('Existing recording state:', {{ $existingRecording ? 'true' : 'false' }}); console.log('Lesson ID:', {{ $lessonId }}); console.log('Has new recording:', {{ $hasNewRecording ? 'true' : 'false' }});">
                @if($hasNewRecording || $existingRecording)
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                        </svg>
                        Re-record
                    </span>
                @else
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                        </svg>
                        Start Recording
                    </span>
                @endif
            </button>
        @else
            <button wire:click="stopRecording" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600" id="stopBtn" onclick="console.log('Stop button clicked')">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd" />
                    </svg>
                    Stop Recording
                </span>
            </button>
        @endif
        
        <div class="flex items-center">
            @if($isRecording)
                <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse mr-2"></div>
                <span class="text-red-500 font-medium">Recording...</span>
            @else
                <span class="text-gray-500">
                    @if($hasNewRecording || $existingRecording)
                        Ready to re-record
                    @else
                        Ready to record
                    @endif
                </span>
            @endif
        </div>
    </div>
    
    <div id="browserSupportWarning" class="mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 hidden" role="alert">
        <p>Your browser does not support audio recording features. Please use a modern browser like Chrome, Firefox, or Edge.</p>
    </div>
    
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Check for browser support
            const isMediaRecorderSupported = typeof MediaRecorder !== 'undefined';
            const isGetUserMediaSupported = navigator.mediaDevices && navigator.mediaDevices.getUserMedia;
            
            console.log('Browser support check:', {
                MediaRecorder: isMediaRecorderSupported,
                getUserMedia: isGetUserMediaSupported
            });
            
            if (!isMediaRecorderSupported) {
                document.getElementById('browserSupportWarning').classList.remove('hidden');
                document.getElementById('startBtn').disabled = true;
                alert('Your browser does not support audio recording. Please use a modern browser like Chrome, Firefox, or Edge.');
                return;
            }
            
            if (!isGetUserMediaSupported) {
                document.getElementById('browserSupportWarning').classList.remove('hidden');
                document.getElementById('startBtn').disabled = true;
                alert('Your browser does not support microphone access. Please use a modern browser.');
                return;
            }
            
            let mediaRecorder;
            let audioChunks = [];
            let audioBlob = null;
            let timerInterval = null;
            let mediaStream = null; // Store reference to the media stream
            
            // Timer handling
            Livewire.on('start-timer', () => {
                console.log('Start timer event received');
                if (timerInterval) {
                    clearInterval(timerInterval);
                }
                
                timerInterval = setInterval(() => {
                    console.log('Timer tick');
                    // Dispatch to Livewire to update the timer
                    @this.call('updateTimer');
                }, 1000);
            });
            
            Livewire.on('stop-timer', () => {
                console.log('Stop timer event received');
                if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
                // Ensure media stream is released when timer stops
                if (mediaStream) {
                    const tracks = mediaStream.getTracks();
                    tracks.forEach(track => {
                        track.stop();
                        console.log('Track stopped on timer stop:', track.kind);
                    });
                    mediaStream = null; // Clear the reference
                }
            });
            
            // Confirmation for overwrite
            Livewire.on('confirm-overwrite', () => {
                console.log('Show overwrite confirmation with SweetAlert2');
                
                Swal.fire({
                    title: 'Overwrite Existing Recording?',
                    text: 'You already have a recording for this lesson. If you proceed, your existing recording will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, overwrite it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('User confirmed overwrite');
                        @this.call('confirmOverwrite');
                    } else {
                        console.log('User cancelled overwrite');
                        // Release the media stream if recording was cancelled
                        if (mediaStream) {
                            const tracks = mediaStream.getTracks();
                            tracks.forEach(track => {
                                track.stop();
                                console.log('Track stopped on cancel:', track.kind);
                            });
                            mediaStream = null; // Clear the reference
                        }
                    }
                });
            });
            
            // Recording deleted event
            Livewire.on('recording-deleted', () => {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Recording has been deleted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
            
            // Recording functionality
            @this.on('startRecording', async () => {
                console.log('StartRecording event received in JavaScript');
                try {
                    console.log('Requesting microphone access...');
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    console.log('Microphone access granted');
                    
                    // Store reference to the media stream
                    mediaStream = stream;
                    
                    mediaRecorder = new MediaRecorder(stream);
                    audioChunks = [];
                    
                    mediaRecorder.ondataavailable = event => {
                        console.log('Data available:', event.data.size, 'bytes');
                        audioChunks.push(event.data);
                    };
                    
                    mediaRecorder.onstop = () => {
                        console.log('MediaRecorder stopped');
                        // Release the media stream tracks to turn off the microphone
                        if (mediaStream) {
                            const tracks = mediaStream.getTracks();
                            tracks.forEach(track => {
                                track.stop();
                                console.log('Track stopped:', track.kind);
                            });
                            mediaStream = null; // Clear the reference
                        }
                        
                        if (audioChunks.length === 0) {
                            console.log('No audio data recorded');
                            Swal.fire({
                                title: 'Error!',
                                text: 'No audio was recorded. Please check your microphone.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }
                        
                        audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                        console.log('Audio blob created', {
                            size: audioBlob.size,
                            type: audioBlob.type
                        });
                        
                        if (audioBlob.size === 0) {
                            console.log('Audio blob is empty');
                            Swal.fire({
                                title: 'Error!',
                                text: 'Recording failed - no audio data captured.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }
                        
                        const audioUrl = URL.createObjectURL(audioBlob);
                        const audio = document.getElementById('previewAudio');
                        
                        if (audio) {
                            audio.src = audioUrl;
                            // Tampilkan preview
                            @this.set('showPreview', true);
                        }
                        
                        // Auto-save when recording stops
                        console.log('Auto-saving recording...');
                        saveRecording(audioBlob);
                    };
                    
                    mediaRecorder.start();
                    console.log('MediaRecorder started');
                } catch (error) {
                    console.error('Error accessing microphone:', error);
                    // Release the media stream if it was created
                    if (mediaStream) {
                        const tracks = mediaStream.getTracks();
                        tracks.forEach(track => {
                            track.stop();
                            console.log('Track stopped on error:', track.kind);
                        });
                        mediaStream = null; // Clear the reference
                    }
                    
                    if (error.name === 'NotAllowedError') {
                        Swal.fire({
                            title: 'Permission Denied!',
                            text: 'Microphone access denied. Please allow microphone access in your browser settings and try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else if (error.name === 'NotFoundError') {
                        Swal.fire({
                            title: 'No Microphone!',
                            text: 'No microphone found. Please check your audio device.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else if (error.name === 'NotReadableError') {
                        Swal.fire({
                            title: 'Microphone Busy!',
                            text: 'Microphone is being used by another application.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Could not access microphone. Please check permissions and device. Error: ' + error.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
            
            @this.on('stopRecording', () => {
                console.log('StopRecording event received in JavaScript');
                if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                    mediaRecorder.stop();
                }
                // Also release the media stream tracks immediately when stop is requested
                if (mediaStream) {
                    const tracks = mediaStream.getTracks();
                    tracks.forEach(track => {
                        track.stop();
                        console.log('Track stopped on manual stop:', track.kind);
                    });
                    mediaStream = null; // Clear the reference
                }
            });
            
            // Save recording function
            async function saveRecording(blob) {
                // Release media stream as soon as we have the blob
                if (mediaStream) {
                    const tracks = mediaStream.getTracks();
                    tracks.forEach(track => {
                        track.stop();
                        console.log('Track stopped on save:', track.kind);
                    });
                    mediaStream = null; // Clear the reference
                }
                
                if (!blob) {
                    console.log('No blob to save');
                    Swal.fire({
                        title: 'Error!',
                        text: 'No recording data to save.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                
                if (blob.size === 0) {
                    console.log('Blob is empty');
                    Swal.fire({
                        title: 'Error!',
                        text: 'Recording is empty, nothing to save.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                
                console.log('Saving recording...', {
                    blobSize: blob.size,
                    blobType: blob.type,
                    lessonId: {{ $lessonId }}
                });
                
                const formData = new FormData();
                formData.append('audio', blob, 'recording.webm');
                formData.append('lesson_id', {{ $lessonId }});
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                try {
                    console.log('Sending request to save recording');
                    const response = await fetch('{{ route("audio.store") }}', {
                        method: 'POST',
                        body: formData
                    });
                    
                    console.log('Response received', {
                        status: response.status,
                        statusText: response.statusText
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const result = await response.json();
                    console.log('Result:', result);
                    
                    if (result.success) {
                        // Update the Livewire component with the new recording path
                        @this.call('updateRecordingDisplay', result.path);
                        
                        // Sembunyikan preview
                        @this.set('showPreview', false);
                        
                        // Show success message
                        Swal.fire({
                            title: 'Success!',
                            text: 'Recording saved successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to save recording: ' + result.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                } catch (error) {
                    console.error('Error saving recording:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to save recording. Please try again. Error: ' + error.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
            
            // Manual save button functionality
            document.addEventListener('click', function(e) {
                if (e.target && e.target.id === 'previewSaveBtn') {
                    console.log('Manual save button clicked');
                    if (audioBlob) {
                        saveRecording(audioBlob);
                    }
                }
            });
            
            // Play button functionality
            document.addEventListener('click', function(e) {
                if (e.target && e.target.id === 'previewPlayBtn') {
                    const audio = document.getElementById('previewAudio');
                    if (audio) {
                        audio.play();
                    }
                }
            });
            
            // Cleanup function to release media stream
            function releaseMediaStream() {
                if (mediaStream) {
                    const tracks = mediaStream.getTracks();
                    tracks.forEach(track => {
                        track.stop();
                        console.log('Track stopped on cleanup:', track.kind);
                    });
                    mediaStream = null;
                }
                if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
            }
            
            // Check and release media stream periodically
            setInterval(() => {
                if (mediaStream && !@this.isRecording) {
                    console.log('Releasing stray media stream');
                    releaseMediaStream();
                }
            }, 5000); // Check every 5 seconds
            
            // Release media stream when page is unloaded
            window.addEventListener('beforeunload', releaseMediaStream);
            
            // Release media stream when Livewire component is destroyed
            Livewire.on('destroy', releaseMediaStream);
        });
    </script>
</div>