async function getSongs() {
  try {
    const response = await fetch("api/spotify/get_songs.php", {
      method: "GET",
    });
    const result = await response.json();

    if (response.ok) {
      const songsContainer = document.getElementById("songsContainer");
      songsContainer.innerHTML = ""; // Clear existing songs

      result.songs.forEach((song) => {
        const songPlayer = player(song); // Use the universal component
        songsContainer.appendChild(songPlayer);
      });
    } else {
      alert(result.error || "Wystąpił błąd");
    }
  } catch (error) {
    console.error("Error fetching songs:", error);
    alert("Wystąpił błąd podczas ładowania piosenek.");
  }
}
