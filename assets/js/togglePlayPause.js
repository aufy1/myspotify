function togglePlayPause(button) {
  const filename = $(button).data("filename");
  const songIndex = $(button).data("songIndex"); // Zmieniamy 'index' na 'songIndex'
  const playIcon = $(button).find(".playIcon");
  const songBox = $(button).closest(".w-full, .w-60");
  const progressBar = songBox.find(".progress-bar");
  const progressFill = progressBar.find(".progress-fill");

  // Sprawdzamy, czy już odtwarzamy ten utwór
  if (currentAudio && currentAudio.src.endsWith(filename)) {
    // Jeśli utwór gra, wstrzymaj lub kontynuuj
    if (currentAudio.paused) {
      currentAudio.play();
      playIcon.attr("src", "media/spotify_icons/pause-solid.svg");
      progressBar.addClass("active");
    } else {
      currentAudio.pause();
      playIcon.attr("src", "media/storage_icons/play-solid.svg");
      progressBar.removeClass("active");
    }
  } else {
    // Jeśli wybrano inny utwór
    if (currentAudio) {
      currentAudio.pause();
      currentAudio = null;

      if (currentPlayButton) {
        const prevPlayIcon = $(currentPlayButton).find(".playIcon");
        prevPlayIcon.attr("src", "media/storage_icons/play-solid.svg");

        if (currentProgressBar) {
          currentProgressBar.removeClass("active");
          currentProgressBar.find(".progress-fill").css("width", "0%");
        }
      }
    }

    // Odtwórz nowy utwór
    currentAudio = new Audio(`uploads/songs/${filename}`);
    currentAudio.play();
    playIcon.attr("src", "media/spotify_icons/pause-solid.svg");
    currentPlayButton = button;
    currentProgressBar = progressBar;
    progressBar.addClass("active");
    progressFill.css("width", "0%");

    $(currentAudio).on("timeupdate", function () {
      if (currentAudio && currentProgressBar) {
        const progress =
          (currentAudio.currentTime / currentAudio.duration) * 100;
        currentProgressBar.find(".progress-fill").css("width", `${progress}%`);
      }
    });

    $(currentAudio).on("ended", function () {
      // Zresetuj poprzedni utwór
      if (currentPlayButton) {
        const prevPlayIcon = $(currentPlayButton).find(".playIcon");
        prevPlayIcon.attr("src", "media/storage_icons/play-solid.svg");
        if (currentProgressBar) {
          currentProgressBar.removeClass("active");
          currentProgressBar.find(".progress-fill").css("width", "0%");
        }
      }

      // Zresetuj zmienne
      currentAudio = null;
      currentPlayButton = null;
      currentProgressBar = null;

      // Jeśli jesteśmy w modal i są kolejne piosenki
      if (isInPlaylistModal && playlistSongs[songIndex + 1]) {
        const nextSong = playlistSongs[songIndex + 1];
        const nextSongButton = document.querySelector(
          `[data-song-index="${songIndex + 1}"]`
        ); // Używamy 'data-song-index'
        togglePlayPause(nextSongButton);
      }
    });
  }

  // Obsługa kliknięcia na pasek postępu
  progressBar.on("click", function (event) {
    if (currentAudio && currentAudio.duration && currentAudio.duration > 0) {
      const progressBarWidth = progressBar.width();
      const clickPosition = event.offsetX;
      const newTime =
        (clickPosition / progressBarWidth) * currentAudio.duration;
      if (!isNaN(newTime) && newTime >= 0 && newTime <= currentAudio.duration) {
        currentAudio.currentTime = newTime;
      }
    }
  });
}
