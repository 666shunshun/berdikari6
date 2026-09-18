document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.product-grid').forEach(grid => {
        [...grid.children].forEach((card, index) => card.dataset.originalOrder = index);
    });
    populateColourFilter();
});

function populateColourFilter() {
    const colourFilter = document.getElementById('colourFilter');
    const colours = new Set();

    document.querySelectorAll('.product-grid > div p').forEach(description => {
        const text = description.textContent.replace(/^Colour:\s*/i, '');
        if (!/^Colour:/i.test(description.textContent)) return;

        text.split('/').forEach(colour => {
            const cleanColour = colour.trim();
            if (cleanColour) colours.add(cleanColour);
        });
    });

    [...colours].sort((a, b) => a.localeCompare(b)).forEach(colour => {
        const option = document.createElement('option');
        option.value = colour.toLowerCase();
        option.textContent = colour;
        colourFilter.append(option);
    });
}

function filterProducts() {
    const keyword = document.getElementById('productSearchInput').value.toLowerCase().trim();
    const selectedColour = document.getElementById('colourFilter').value;
    const sortOrder = document.getElementById('sortSelect').value;
    const clearBtn = document.getElementById('clearSearchBtn');
    clearBtn.style.display = keyword ? 'flex' : 'none';

    document.querySelectorAll('.category-box').forEach((categoryBox, categoryIndex) => {
        const categoryName = categoryBox.querySelector('.category-header').textContent.toLowerCase();
        let categoryHasMatch = false;

        categoryBox.querySelectorAll('.brand-box').forEach((brandBox, brandIndex) => {
            const brandName = brandBox.querySelector('.brand-header').textContent.toLowerCase();
            const cards = [...brandBox.querySelectorAll('.product-grid > div')];
            const grid = brandBox.querySelector('.product-grid');
            let brandHasMatch = false;

            cards.forEach(card => {
                const title = card.querySelector('h4').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                const matchesKeyword = !keyword || title.includes(keyword) || description.includes(keyword) || brandName.includes(keyword) || categoryName.includes(keyword);
                const matchesColour = !selectedColour || description.includes(selectedColour);
                const isMatch = matchesKeyword && matchesColour;

                card.style.display = isMatch ? '' : 'none';
                brandHasMatch ||= isMatch;
            });

            cards.sort((a, b) => {
                if (sortOrder === 'default') return Number(a.dataset.originalOrder) - Number(b.dataset.originalOrder);
                return a.querySelector('h4').textContent.localeCompare(b.querySelector('h4').textContent) * (sortOrder === 'az' ? 1 : -1);
            }).forEach(card => grid.append(card));

            brandBox.style.display = brandHasMatch ? '' : 'none';
            brandBox.open = (keyword || selectedColour) ? brandHasMatch : brandIndex === 0;
            categoryHasMatch ||= brandHasMatch;
        });

        categoryBox.style.display = categoryHasMatch ? '' : 'none';
        categoryBox.open = (keyword || selectedColour) ? categoryHasMatch : categoryIndex === 0;
    });
}

function clearSearch() {
    document.getElementById('productSearchInput').value = '';
    document.getElementById('colourFilter').value = '';
    document.getElementById('sortSelect').value = 'default';
    filterProducts();
    document.getElementById('productSearchInput').focus();
}
