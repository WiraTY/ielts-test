<div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <nav class="flex items-center text-sm text-gray-500 mb-4">
                <a href="{{ route('courses.index') }}" class="hover:text-blue-600">Courses</a>
                <span class="mx-2">/</span>
                <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-blue-600">{{ $course->title }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800">{{ $lesson->title }}</span>
            </nav>
            
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $lesson->title }}</h1>
            
            @if($lesson->video_url)
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Video</h2>
                    <div class="w-full max-w-4xl mx-auto rounded-lg overflow-hidden bg-gray-100 relative" style="height: 500px;">
                        <!-- Video player component using YouTube API -->
                        <div id="player-{{ $lesson->id }}"></div>
                        
                        <!-- Custom play button for mobile -->
                        <div id="play-button-{{ $lesson->id }}" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 cursor-pointer z-10" style="display: none;">
                            <div class="bg-white rounded-full p-4 shadow-lg hover:bg-gray-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="content-editor max-w-none mb-6">
                {!! $lesson->content !!}
            </div>
            
            <div class="flex items-center justify-between mt-8">
                <div>
                    @if($progress && $progress->status === 'completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Completed
                        </span>
                    @else
                        <button wire:click="markAsCompleted" 
                                class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                            Mark as Completed
                        </button>
                    @endif
                </div>
                
                <div>
                    @if($lesson->quizzes && $lesson->quizzes->count() > 0)
                        <a href="{{ route('quizzes.start', $lesson->quizzes->first()->id) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Take Quiz
                        </a>
                    @else
                        <!-- No quiz for this lesson -->
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .content-editor {
            line-height: 1.6;
        }
        
        .content-editor p {
            margin-bottom: 1rem;
        }
        
        .content-editor h1,
        .content-editor h2,
        .content-editor h3,
        .content-editor h4,
        .content-editor h5,
        .content-editor h6 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .content-editor h1 {
            font-size: 2rem;
            line-height: 1.2;
        }
        
        .content-editor h2 {
            font-size: 1.5rem;
            line-height: 1.3;
        }
        
        .content-editor h3 {
            font-size: 1.25rem;
            line-height: 1.4;
        }
        
        .content-editor ul,
        .content-editor ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }
        
        .content-editor ul li {
            list-style-type: disc;
            margin-bottom: 0.5rem;
        }
        
        .content-editor ol li {
            list-style-type: decimal;
            margin-bottom: 0.5rem;
        }
        
        .content-editor img {
            max-width: 100%;
            height: auto;
            margin: 1rem 0;
        }
        
        .content-editor a {
            color: #3b82f6;
            text-decoration: underline;
        }
        
        .content-editor a:hover {
            color: #2563eb;
        }
        
        .content-editor blockquote {
            border-left: 4px solid #d1d5db;
            padding-left: 1rem;
            margin: 1rem 0;
            color: #4b5563;
        }
        
        .content-editor pre {
            background-color: #f3f4f6;
            padding: 1rem;
            border-radius: 0.375rem;
            overflow-x: auto;
            margin: 1rem 0;
        }
        
        .content-editor code {
            background-color: #f3f4f6;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }
        
        .content-editor pre code {
            background-color: transparent;
            padding: 0;
        }
    </style>
    
    @if($lesson->video_url)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Extract video ID from URL
            const videoUrl = "{{ $lesson->video_url }}";
            const videoId = videoUrl.split('embed/')[1] || videoUrl.split('v=')[1]?.split('&')[0];
            
            if (!videoId) return;
            
            let player;
            const playButton = document.getElementById('play-button-{{ $lesson->id }}');
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            
            // Show play button on mobile devices
            if (isMobile) {
                playButton.style.display = 'flex';
            }
            
            // Load YouTube API
            if (typeof YT === 'undefined') {
                const tag = document.createElement('script');
                tag.src = "https://www.youtube.com/iframe_api";
                const firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                
                window.onYouTubeIframeAPIReady = function() {
                    createPlayer();
                };
            } else {
                createPlayer();
            }
            
            function createPlayer() {
                player = new YT.Player('player-{{ $lesson->id }}', {
                    height: '500',
                    width: '100%',
                    videoId: videoId,
                    playerVars: {
                        'rel': 0,
                        'modestbranding': 1,
                        'autoplay': 0,
                        'playsinline': 1
                    },
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                    }
                });
            }
            
            function onPlayerReady(event) {
                // Hide play button when player is ready on desktop
                if (!isMobile) {
                    playButton.style.display = 'none';
                    event.target.playVideo();
                }
            }
            
            function onPlayerStateChange(event) {
                // Hide play button when video starts playing
                if (event.data == YT.PlayerState.PLAYING) {
                    playButton.style.display = 'none';
                }
            }
            
            // Play button click handler
            playButton.addEventListener('click', function() {
                if (player && typeof player.playVideo === 'function') {
                    player.playVideo();
                }
            });
        });
    </script>
    @endif
</div>
