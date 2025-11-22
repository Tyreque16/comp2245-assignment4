document.addEventListener("DOMContentLoaded", function () {
    const countryInput = document.getElementById("country");
    const resultDiv = document.getElementById("result");

    // Lookup Country button
    document.getElementById("lookup").addEventListener("click", function () {
        const country = countryInput.value.trim();
        fetch(`world.php?country=${encodeURIComponent(country)}`)
            .then(response => response.text())
            .then(data => {
                resultDiv.innerHTML = data;
            })
            .catch(() => {
                resultDiv.innerHTML = "Error fetching country data.";
            });
    });

    // Lookup Cities button
    document.getElementById("lookup-cities").addEventListener("click", function () {
        const country = countryInput.value.trim();
        if (country === "") {
            resultDiv.innerHTML = "Please enter a country name to lookup cities.";
            return;
        }
        fetch(`world.php?country=${encodeURIComponent(country)}&lookup=cities`)
            .then(response => response.text())
            .then(data => {
                resultDiv.innerHTML = data;
            })
            .catch(() => {
                resultDiv.innerHTML = "Error fetching city data.";
            });
    });
});
