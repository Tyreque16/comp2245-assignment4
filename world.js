// world.js
document.addEventListener("DOMContentLoaded", function () {
    // Make sure the elements exist
    const lookupBtn = document.getElementById("lookup");
    const countryInput = document.getElementById("country");
    const resultDiv = document.getElementById("result");

    if (!lookupBtn || !countryInput || !resultDiv) {
        console.error("One or more elements not found: #lookup, #country, #result");
        return;
    }

    lookupBtn.addEventListener("click", function () {
        const country = countryInput.value.trim();

        // Debug: confirm button works
        console.log("Lookup clicked. Country:", country);

        fetch(`world.php?country=${encodeURIComponent(country)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.text();
            })
            .then(data => {
                console.log("Data received:", data); // Debug: check what PHP returns
                resultDiv.innerHTML = data;
            })
            .catch(error => {
                console.error("Fetch error:", error);
                resultDiv.innerHTML = "Error fetching data.";
            });
    });
});
