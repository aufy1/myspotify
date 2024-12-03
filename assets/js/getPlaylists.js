async function getPlaylists(allPublic = false) {
  try {
    // Dodajemy parametr all_public=true, jeśli allPublic jest ustawione na true
    const url = allPublic
      ? "api/spotify/get_playlists.php?all_public=true"
      : "api/spotify/get_playlists.php";

    const response = await fetch(url, {
      method: "GET",
    });
    const result = await response.json();

    if (response.ok) {
      const playlistsContainer = document.getElementById("playlistsContainer");
      playlistsContainer.innerHTML = ""; // Clear existing playlists

      // Iterate through playlists and generate boxes
      result.playlists.forEach((playlist) => {
        const playlistBox = document.createElement("div");
        playlistBox.classList.add(
          "w-60",
          "h-60",
          "bg-gray-700",
          "rounded",
          "flex",
          "flex-col",
          "items-center",
          "justify-center",
          "shadow-lg",
          "relative",
          "overflow-hidden", // Ensure content stays within the box
          "transition-transform", // Enable smooth scaling effect
          "duration-300", // Duration of the scaling effect
          "hover:scale-105" // Scale slightly on hover
        );

        playlistBox.innerHTML = `
          <div class="absolute inset-0 -mt-12 flex items-center justify-center">
              <div class="bg-gray-500 opacity-50 p-6 rounded-2xl">
                  <img src="media/spotify_icons/list-solid.svg" alt="Playlist Icon" class="w-12 h-12 text-gray-200" />
              </div>
          </div>
          <div class="absolute bottom-2 left-2 right-2 text-center">
              <h3 class="text-xl font-semibold text-white">${playlist.name}</h3>
              <p class="text-sm text-gray-400">${
                playlist.public == "1" ? "Publiczna" : "Prywatna"
              }</p>
          </div>
        `;

        if (!allPublic) {
          // Left button (Gear icon)
          playlistBox.innerHTML += `
            <button 
                class="viewPlaylistButton absolute bottom-2 left-2 opacity-90 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-3 flex items-center justify-center" 
                data-playlist-id="${playlist.id}">
                <img src="media/spotify_icons/gear-solid.svg" alt="View Playlist Icon" class="viewPlaylistIcon w-6 h-6" />
            </button>
          `;
        }

        // Add event listener for box click (excluding the gear button)
        playlistBox.addEventListener("click", (e) => {
          if (!e.target.closest(".viewPlaylistButton")) {
            openPlaylistModal(
              playlist.name,
              playlist.public == "1" ? "Publiczna" : "Prywatna",
              playlist.id
            );
          }
        });

        playlistsContainer.appendChild(playlistBox);
      });

      if (!allPublic) {
        document.querySelectorAll(".viewPlaylistButton").forEach((button) => {
          button.addEventListener("click", (e) => {
            e.stopPropagation();
            viewPlaylistDetails(button);
          });
        });
      }
    } else {
      alert(result.error || "Wystąpił błąd");
    }
  } catch (error) {
    console.error("Error fetching playlists:", error);
    alert("Wystąpił błąd podczas ładowania playlist.");
  }
}
