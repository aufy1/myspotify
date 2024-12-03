let playlistSongs = []; // Zmienna globalna
let isInPlaylistModal = false; // Initialize the variable

async function openPlaylistModal(title, details, playlistId) {
  isInPlaylistModal = true;
  console.log("Opening modal for playlistId:", playlistId);

  const playlistModal = document.getElementById("playlistModal");
  const modalPlaylistTitle = document.getElementById("modalPlaylistTitle");
  const modalPlaylistDetails = document.getElementById("modalPlaylistDetails");
  const modalSongsContainer = document.getElementById("modalSongsContainer");
  const body = document.body; // Pobranie elementu body

  // Ustawienia modala
  modalPlaylistTitle.textContent = title;
  modalPlaylistDetails.textContent = `Status: ${details}`;
  modalSongsContainer.innerHTML =
    '<p class="text-gray-400">Ładowanie piosenek...</p>';

  try {
    const response = await fetch(
      `api/spotify/get_playlist_songs.php?playlist_id=${playlistId}`,
      { method: "GET" }
    );
    const result = await response.json();

    console.log("API Response:", result);

    if (
      response.ok &&
      result.songs &&
      Array.isArray(result.songs) &&
      result.songs.length > 0
    ) {
      playlistSongs = result.songs; // Przechowaj piosenki w zmiennej globalnej
      modalSongsContainer.innerHTML = ""; // Wyczyszczenie placeholdera

      result.songs.forEach((song, index) => {
        console.log("Adding song:", song);
        const songPlayer = player(song, "wide");
        songPlayer.querySelector(".playButton").dataset.songIndex = index; // Ustawienie indeksu piosenki
        modalSongsContainer.appendChild(songPlayer);
      });
    } else {
      modalSongsContainer.innerHTML = `<p class="text-gray-400">${
        result.error || "Brak piosenek w playliście."
      }</p>`;
    }
  } catch (error) {
    console.error("Error fetching playlist songs:", error);
    modalSongsContainer.innerHTML =
      '<p class="text-gray-400">Wystąpił błąd podczas ładowania piosenek.</p>';
  }

  // Pokaż modal i zablokuj przewijanie tła
  playlistModal.classList.remove("hidden");
  body.classList.add("overflow-hidden"); // Dodanie klasy Tailwind
}

function closePlaylistModal() {
  isInPlaylistModal = false;
  const playlistModal = document.getElementById("playlistModal");
  const body = document.body; // Pobranie elementu body

  // Sprawdź, czy obecnie odtwarzane audio jest związane z modalem
  if (
    currentAudio &&
    currentPlayButton &&
    playlistModal.contains(currentPlayButton)
  ) {
    // Zatrzymaj odtwarzanie
    currentAudio.pause();
    currentAudio.currentTime = 0; // Ustaw czas na początek

    // Resetuj progress bar
    if (currentProgressBar) {
      currentProgressBar.find(".progress-fill").css("width", "0%");
      currentProgressBar.removeClass("active");
    }

    // Zresetuj zmienne globalne
    currentAudio = null; // Resetowanie aktualnego audio
    currentProgressBar = null; // Resetowanie referencji do progress bara
    currentPlayButton = null; // Resetowanie przycisku play
  }

  // Ukryj modal
  playlistModal.classList.add("hidden");
  body.classList.remove("overflow-hidden"); // Przywrócenie przewijania strony
}

document
  .getElementById("closeModalButton")
  .addEventListener("click", closePlaylistModal);

function openModal() {
  playlistModal.classList.remove("hidden");
}

function closeModal() {
  playlistModal.classList.add("hidden");
}

closeModalButton.addEventListener("click", closeModal);

document.getElementById("playlistsContainer").addEventListener("click", (e) => {
  const target = e.target;
  // Upewnij się, że kliknięcie nie dotyczy przycisku "gear"
  if (!target.closest(".viewPlaylistButton")) {
    const playlistBox = target.closest('div[class*="playlistBox"]');
    if (playlistBox) {
      openModal();
    }
  }
});
