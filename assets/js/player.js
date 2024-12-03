function player(song, playerType = "square") {
  const songBox = document.createElement("div");

  if (playerType === "square") {
    songBox.classList.add(
      "w-60",
      "h-60",
      "bg-gray-700",
      "rounded-3xl",
      "flex",
      "flex-col",
      "items-center",
      "justify-center",
      "shadow-lg",
      "relative",
      "overflow-hidden",
      "transition-transform",
      "duration-300",
      "hover:scale-105"
    );

    songBox.innerHTML = `
            <div 
                class="progress-bar w-full bg-gray-600 rounded-t-2xl absolute top-0 left-0 transition-all duration-300 hover:h-2 z-10">
                <div 
                    class="progress-fill h-full bg-green-400 rounded-t-2xl transition-all duration-300" 
                    style="width: 0%;">
                </div>
            </div>

            <div class="absolute inset-0 -mt-12 flex items-center justify-center">
                <div class="bg-gray-500 opacity-50 p-6 rounded-full">
                    <img src="media/spotify_icons/music-solid.svg" alt="Music Icon" class="w-12 h-12 text-gray-200" />
                </div>
            </div>

            <div class="absolute bottom-2 left-2 right-2 text-center">
                <h3 class="text-xl font-semibold text-white">${song.title}</h3>
                <p class="text-sm text-gray-400">${song.musician}</p>
            </div>

            <button 
                class="playButton absolute bottom-2 right-2 bg-green-600 hover:bg-green-700 text-white rounded-full p-3 flex items-center justify-center" 
                data-filename="${song.filename}">
                <img src="media/storage_icons/play-solid.svg" alt="Play Icon" class="playIcon w-6 h-6" />
            </button>
        `;
  } else if (playerType === "wide") {
    songBox.classList.add(
      "w-full",
      "h-24",
      "bg-gray-700",
      "rounded-xl",
      "flex",
      "items-center",
      "justify-between",
      "shadow-md",
      "relative",
      "overflow-hidden",
      "transition-transform",
      "duration-300",
      "hover:scale-105"
    );

    songBox.innerHTML = `
            <div class="flex items-center h-full px-4">
                <div class="bg-gray-600 p-4 rounded-full">
                    <img src="media/spotify_icons/music-solid.svg" alt="Music Icon" class="w-8 h-8 text-gray-200" />
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-white inline">${song.title}</h3>
                    <span class="text-sm text-gray-400 ml-2">by ${song.musician}</span>
                </div>
            </div>

            <div class="flex items-center h-full px-4">
                <button 
                    class="playButton bg-green-600 hover:bg-green-700 text-white rounded-full p-3 flex items-center justify-center" 
                    data-filename="${song.filename}">
                    <img src="media/storage_icons/play-solid.svg" alt="Play Icon" class="playIcon w-6 h-6" />
                </button>
            </div>

            <!-- Progress bar -->
            <div 
                class="progress-bar w-full bg-gray-600 absolute bottom-0 left-0 h-1 z-10">
                <div 
                    class="progress-fill h-full bg-green-400 transition-all duration-300" 
                    style="width: 0%;">
                </div>
            </div>
        `;
  }

  // Add play button functionality
  const playButton = songBox.querySelector(".playButton");
  playButton.addEventListener("click", () => togglePlayPause(playButton));

  return songBox;
}
