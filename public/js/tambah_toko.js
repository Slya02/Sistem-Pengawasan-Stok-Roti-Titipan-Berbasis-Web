$(document).ready(function () {
    console.log("tambah_toko.js loaded");

    // ==== MAP ====
    let map, marker, selectedLatLng, userMarker;

    $("#btnMap").on("click", function () {
        $("#mapModal").removeClass("hidden").addClass("flex");

        setTimeout(() => {
            if (!map) {
                map = L.map("map").setView([3.5952, 98.6722], 13);

                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution: "&copy; OpenStreetMap contributors",
                }).addTo(map);

                map.on("click", function (e) {
                    selectedLatLng = e.latlng;

                    if (marker) marker.setLatLng(e.latlng);
                    else marker = L.marker(e.latlng).addTo(map);
                });

                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;

                            const userIcon = L.icon({
                                iconUrl: "https://cdn-icons-png.flaticon.com/512/64/64113.png",
                                iconSize: [32, 32],
                                iconAnchor: [16, 32],
                            });

                            userMarker = L.marker([lat, lng], { icon: userIcon })
                                .addTo(map)
                                .bindPopup("<b>Lokasi Anda Saat Ini</b>")
                                .openPopup();

                            map.setView([lat, lng], 14);
                        }
                    );
                }
            } else {
                map.invalidateSize();
            }
        }, 300);
    });

    $("#closeMap").on("click", function () {
        $("#mapModal").addClass("hidden").removeClass("flex");
    });

    $("#saveMap").on("click", function () {
        if (!selectedLatLng) {
            Swal.fire({
                icon: "warning",
                title: "Pilih lokasi dulu!",
                timer: 1500,
                showConfirmButton: false,
            });
            return;
        }

        const lat = selectedLatLng.lat.toFixed(6);
        const lng = selectedLatLng.lng.toFixed(6);

        $("#latitude").val(lat);
        $("#longitude").val(lng);

        const nominatimUrl =
            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&addressdetails=1`;

        fetch(nominatimUrl)
            .then((res) => res.json())
            .then((data) => {
                let alamat = data.display_name ?? "Alamat tidak ditemukan";
                $("#address").val(alamat);

                Swal.fire({
                    icon: "success",
                    title: "Lokasi berhasil dipilih!",
                    text: "Alamat otomatis diisi.",
                    timer: 1800,
                    showConfirmButton: false,
                });

                setTimeout(() => {
                    $("#mapModal").addClass("hidden").removeClass("flex");
                }, 120);
            });
    });

    // ==== Submit tanpa foto ====
    $("#formTambahToko").on("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: $("#formTambahToko").attr("action"),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            beforeSend: function () {
                Swal.fire({
                    title: "Menyimpan data...",
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                });
            },

            success: function () {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: "Data toko berhasil disimpan!",
                    timer: 2000,
                    showConfirmButton: false,
                });
                $("#formTambahToko")[0].reset();
            },

            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Gagal!",
                    text: xhr.responseJSON?.message ?? "Terjadi kesalahan.",
                });
            },
        });
    });
});
