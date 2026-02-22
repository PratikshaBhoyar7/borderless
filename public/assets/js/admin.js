/* Admin Panel JavaScript */

document.addEventListener('DOMContentLoaded', function () {
    initSidebarToggle();
    initTableFunctionality();
    initAlertAutoClose();
    initSearchFilter();
});

/* ==================== SIDEBAR TOGGLE ==================== */

function initSidebarToggle() {
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');

    if (menuToggle) {
        menuToggle.addEventListener('click', function (e) {
            e.preventDefault();
            toggleSidebar();
        });
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function (e) {
            e.preventDefault();
            toggleSidebar();
        });
    }

    // Close sidebar when a menu item is clicked on mobile
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('show');
            }
        });
    });

    // Close sidebar when clicking outside
    document.addEventListener('click', function (e) {
        if (!sidebar.contains(e.target) && !menuToggle || e.target === menuToggle) {
            return;
        }
        if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });
}

function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('collapsed');
    sidebar.classList.toggle('show');
}

/* ==================== ACTIVE MENU ITEM ==================== */

function setActiveMenuItem() {
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('.sidebar-menu a');

    menuLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href)) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

// Call on page load
setActiveMenuItem();

/* ==================== TABLE FUNCTIONALITY ==================== */

function initTableFunctionality() {
    makeTableSortable();
    updateRecordsCount();
}

/* Table Sorting */
function makeTableSortable() {
    const tableHeaders = document.querySelectorAll('.records-table th[onclick]');

    tableHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function () {
            const columnName = this.getAttribute('onclick').match(/'([^']+)'/)[1];
            sortTable(columnName);
        });
    });
}

function sortTable(columnName) {
    const table = document.querySelector('.records-table');
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    // Find the column index
    const headers = Array.from(table.querySelectorAll('th'));
    let columnIndex = -1;

    for (let i = 0; i < headers.length; i++) {
        if (headers[i].textContent.toLowerCase().includes(columnName.toLowerCase())) {
            columnIndex = i;
            break;
        }
    }

    if (columnIndex === -1) return;

    // Check if it's sorted ascending or descending
    const isAscending = !table.dataset.sortAscending || table.dataset.sortAscending !== columnName;
    table.dataset.sortAscending = isAscending ? columnName : '';

    // Sort the rows
    rows.sort((a, b) => {
        const aValue = a.children[columnIndex].textContent.trim();
        const bValue = b.children[columnIndex].textContent.trim();

        // Try to parse as number
        const aNum = parseFloat(aValue);
        const bNum = parseFloat(bValue);

        if (!isNaN(aNum) && !isNaN(bNum)) {
            return isAscending ? aNum - bNum : bNum - aNum;
        }

        // Sort as string
        return isAscending
            ? aValue.localeCompare(bValue)
            : bValue.localeCompare(aValue);
    });

    // Update the table
    rows.forEach(row => tbody.appendChild(row));
}

/* ==================== SEARCH & FILTER ==================== */

function initSearchFilter() {
    const searchInput = document.getElementById('searchInput');
    const filterSelects = document.querySelectorAll('.filter-group select');

    if (searchInput) {
        searchInput.addEventListener('keyup', searchRecords);
    }

    filterSelects.forEach(select => {
        select.addEventListener('change', applyFilters);
    });
}

function searchRecords() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    const searchTerm = searchInput.value.toLowerCase();
    const table = document.querySelector('.records-table');
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    updateRecordsCount(visibleCount);
}

function applyFilters() {
    const searchInput = document.getElementById('searchInput');
    const dateFilter = document.getElementById('dateFilter');
    const sexFilter = document.getElementById('sexFilter');

    if (searchInput) {
        searchRecords();
    }

    const table = document.querySelector('.records-table');
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr');
    let visibleCount = 0;

    rows.forEach(row => {
        let show = true;

        // Search filter
        if (searchInput && searchInput.value) {
            const searchTerm = searchInput.value.toLowerCase();
            show = show && row.textContent.toLowerCase().includes(searchTerm);
        }

        // Date filter
        if (dateFilter && dateFilter.value) {
            const dateCell = row.querySelector('td:nth-child(6)');
            if (dateCell) {
                const rowDate = new Date(dateCell.textContent);
                const today = new Date();
                const startDate = new Date(today);

                switch (dateFilter.value) {
                    case 'today':
                        show = show && rowDate.toDateString() === today.toDateString();
                        break;
                    case 'week':
                        startDate.setDate(today.getDate() - 7);
                        show = show && rowDate >= startDate;
                        break;
                    case 'month':
                        startDate.setMonth(today.getMonth() - 1);
                        show = show && rowDate >= startDate;
                        break;
                }
            }
        }

        // Sex filter
        if (sexFilter && sexFilter.value) {
            const sexCell = row.querySelector('td:nth-child(3)');
            if (sexCell) {
                show = show && sexCell.textContent.includes(sexFilter.value);
            }
        }

        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });

    updateRecordsCount(visibleCount);
}

function updateRecordsCount(count = null) {
    const recordsCountElement = document.getElementById('recordsCount');
    if (!recordsCountElement) return;

    if (count === null) {
        const table = document.querySelector('.records-table');
        if (!table) return;
        count = table.querySelectorAll('tbody tr:not([style*="display: none"])').length;
    }

    recordsCountElement.textContent = count;
}

/* ==================== ALERT AUTO-CLOSE ==================== */

function initAlertAutoClose() {
    const alerts = document.querySelectorAll('.alert:not(.alert-dismissible)');

    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            alert.style.transition = 'all 0.3s ease';

            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // Handle dismissible alerts
    const dismissibleAlerts = document.querySelectorAll('.alert-dismissible');
    dismissibleAlerts.forEach(alert => {
        const closeBtn = alert.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                alert.style.transition = 'all 0.3s ease';

                setTimeout(() => {
                    alert.remove();
                }, 300);
            });
        }

        // Also auto-close after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                alert.style.transition = 'all 0.3s ease';

                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 300);
            }
        }, 5000);
    });
}

/* ==================== FORM VALIDATION ==================== */

function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });
}

// Call on page load
initFormValidation();

/* ==================== UTILITY FUNCTIONS ==================== */

function formatDate(date) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(date).toLocaleDateString('en-US', options);
}

function showSuccessMessage(message) {
    const successDiv = document.getElementById('successMessage');
    if (successDiv) {
        successDiv.textContent = message || 'Operation completed successfully!';
        successDiv.classList.add('show');

        setTimeout(() => {
            successDiv.classList.remove('show');
        }, 5000);
    }
}

function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = '/logout';
    }
}

/* ==================== RESPONSIVE BEHAVIOR ==================== */

window.addEventListener('resize', function () {
    const sidebar = document.querySelector('.sidebar');
    if (window.innerWidth > 768) {
        sidebar.classList.remove('show');
    }
});

/* ==================== SECTION NAVIGATION (for patient system) ==================== */

function showSection(sectionId) {
    const sections = document.querySelectorAll('.section');
    const links = document.querySelectorAll('.sidebar-menu a');

    sections.forEach(section => {
        section.classList.remove('active');
    });

    links.forEach(link => {
        link.classList.remove('active');
    });

    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active');
    }

    const activeLink = document.querySelector(`a[data-section="${sectionId}"]`);
    if (activeLink) {
        activeLink.classList.add('active');
    }
}

/* ==================== EXPORT FUNCTIONS ==================== */

function exportToCSV() {
    const table = document.querySelector('.records-table');
    if (!table) {
        alert('No table found to export');
        return;
    }

    let csv = [];
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const csvRow = [];

        cols.forEach(col => {
            csvRow.push('"' + col.textContent.trim().replace(/"/g, '""') + '"');
        });

        csv.push(csvRow.join(','));
    });

    downloadCSV(csv.join('\n'), 'records.csv');
}

function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.href = URL.createObjectURL(csvFile);
    downloadLink.download = filename;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

function printRecords() {
    const table = document.querySelector('.records-table');
    if (!table) {
        alert('No table found to print');
        return;
    }

    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Records</title>');
    printWindow.document.write('<style>table { border-collapse: collapse; width: 100%; }');
    printWindow.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }');
    printWindow.document.write('th { background-color: #f8f9fa; }</style></head><body>');
    printWindow.document.write(table.outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
