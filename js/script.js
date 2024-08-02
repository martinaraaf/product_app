document.getElementById('productType').addEventListener('change', function () {
        var type = this.value;
        var detailsDiv = document.getElementById('productDetails');
        detailsDiv.innerHTML = '';
    
        if (type === 'DVD') {
            detailsDiv.innerHTML = '<label for="size">Size (MB)</label><input type="number" id="size" name="size" required>';
        } else if (type === 'Book') {
            detailsDiv.innerHTML = '<label for="weight">Weight (Kg)</label><input type="number" id="weight" name="weight" required>';
        } else if (type === 'Furniture') {
            detailsDiv.innerHTML = '<label for="height">Height (CM)</label><input type="number" id="height" name="height" required>';
            detailsDiv.innerHTML += '<label for="width">Width (CM)</label><input type="number" id="width" name="width" required>';
            detailsDiv.innerHTML += '<label for="length">Length (CM)</label><input type="number" id="length" name="length" required>';
        }
    });
    
