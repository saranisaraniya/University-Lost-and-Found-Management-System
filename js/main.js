// Luxury Filter & Dynamic Forms
function filterListings() {
    const searchVal = document.getElementById('search-input')?.value.toLowerCase() || '';
    const catVal = document.getElementById('category-filter')?.value.toLowerCase() || '';
    const typeVal = document.getElementById('type-filter')?.value.toLowerCase() || '';
    
    const itemCards = document.querySelectorAll('#items-grid > div');
    let visibleCount = 0;

    itemCards.forEach(card => {
        const text = card.textContent.toLowerCase();
        const category = card.getAttribute('data-category') || '';
        const type = card.getAttribute('data-type') || '';

        const matchesSearch = text.includes(searchVal);
        const matchesCategory = !catVal || category.toLowerCase() === catVal;
        const matchesType = !typeVal || type.toLowerCase() === typeVal;

        if (matchesSearch && matchesCategory && matchesType) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    const countEl = document.getElementById('item-count');
    if (countEl) {
        countEl.textContent = visibleCount + ' Verified Listings';
    }
}

function toggleRegField() {
    const userType = document.getElementById('user_type')?.value;
    const labelEl = document.getElementById('reg_label');
    const inputEl = document.getElementById('reg_input');

    if (userType === 'staff') {
        if (labelEl) labelEl.textContent = 'Staff Employee ID*';
        if (inputEl) inputEl.placeholder = 'e.g. ST/PHYS/102';
    } else {
        if (labelEl) labelEl.textContent = 'Student Reg No*';
        if (inputEl) inputEl.placeholder = 'e.g. 2021/ICT/85';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    filterListings();
});
