class DataTable {
    constructor(config) {
        // Configuration with defaults
        this.config = {
            tableId: '',
            route: '',
            perPageOptions: [10, 25, 50, 100],
            defaultPerPage: 10,
            sortColumn: 'created_at',
            sortDirection: 'desc',
            renderRow: null,
            actionHandlers: {}, // Object to store action handlers
            actionRoutes: {}, // Object to store action routes
            ...config
        };

        // State management
        this.state = {
            currentPage: 1,
            perPage: this.config.defaultPerPage,
            totalItems: 0,
            totalPages: 0,
            loading: false,
            searchQuery: ''
        };

        this.additionalParams = {};
        // Initialize the table

        // this.init();
    }

    init() {

        this.bindEvents();
        this.initializeElements();
        this.loadData(this.additionalParams);
    }

    setAdditionalParams(params) {
        this.additionalParams = { ...this.additionalParams, ...params };
        this.loadData(this.additionalParams);
    }

    initializeElements() {
        // Initialize per page select dropdown
        const perPageSelect = $('#perPageSelect');
        perPageSelect.empty();
        this.config.perPageOptions.forEach(option => {
            perPageSelect.append(`<option value="${option}" ${option === this.state.perPage ? 'selected' : ''}>${option}</option>`);
        });
    }

    bindEvents() {
        // Bind action buttons if handlers are provided
        if (this.config.actionHandlers) {
            Object.keys(this.config.actionHandlers).forEach(action => {
                $(document).on('click', `#${this.config.tableId} .btn-${action}`, (e) => {
                    e.preventDefault();
                    const itemId = $(e.currentTarget).data('id');
                    this.config.actionHandlers[action].call(this, itemId);
                });
            });
        }

        // Per page change event
        $(`#perPageSelect`).on('change', () => {
            this.state.perPage = parseInt($(`#perPageSelect`).val());
            this.state.currentPage = 1;
            this.loadData(this.additionalParams);
        });

        // Search button click event
        $('#searchButton').on('click', () => {
            this.state.searchQuery = $('#searchDatatable').val();
            this.state.currentPage = 1;
            this.loadData(this.additionalParams);
        });

        // Debounced search input
        let typingTimer;
        $('#searchDatatable').on('keyup', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                this.state.searchQuery = $('#searchDatatable').val();
                this.state.currentPage = 1;
                this.loadData(this.additionalParams);
            }, 500);
        });
    }

    loadData(additionalParams = {}) {
        if (this.state.loading) return;
        this.state.loading = true;

        // Show loading state
        $(`#${this.config.tableId} tbody`).html(this.getLoadingRow());
        // console.log("additionla params",additionalParams);

        $.ajax({
            url: this.config.route,
            type: "GET",
            data: {
                page: this.state.currentPage,
                per_page: this.state.perPage,
                search: this.state.searchQuery,
                sort_column: this.config.sortColumn,
                sort_direction: this.config.sortDirection,
                ...additionalParams
            },
            success: (response) => {
                this.state.totalItems = response.filteredRecords;
                this.state.totalPages = Math.ceil(this.state.totalItems / this.state.perPage);
                this.renderTable(response.records);
                this.renderPagination();
                this.updateTableInfo();
                this.state.loading = false;
            },
            error: () => {
                $(`#${this.config.tableId} tbody`).html(this.getErrorRow());
                this.state.loading = false;
            }
        });
    }

    renderTable(data) {
        const tbody = $(`#${this.config.tableId} tbody`);
        tbody.empty();

        if (data.length === 0) {
            tbody.html(this.getNoDataRow());
            return;
        }

        data.forEach((item, index) => {
            const rowHtml = this.config.renderRow
                ? this.config.renderRow.call(this, item, index, this.state.currentPage, this.state.perPage)
                : this.getDefaultRow(item, index);
            tbody.append(rowHtml);
        });
    }

    getDefaultRow(item, index) {
        const sn = (this.state.currentPage - 1) * this.state.perPage + index + 1;
        return `
            <tr>
                <td>${sn}</td>
                <td colspan="4" class="text-center">Implement custom renderRow in config</td>
            </tr>
        `;
    }

    renderPagination() {
        const pagination = $('#paginationControls');
        pagination.empty();

        if (this.state.totalPages <= 1) return;

        const buttonStyle = 'btn btn-sm btn-icon btn-light-primary me-2';
        const activeButtonStyle = 'btn btn-sm btn-icon btn-primary me-2';
        const disabledButtonStyle = 'btn btn-sm btn-icon btn-light me-2 disabled';

        // Store reference to the current DataTable instance
        const dataTable = this;

        // Previous button
        const prevButton = $(`
        <button class="${this.state.currentPage === 1 ? disabledButtonStyle : buttonStyle}"
            ${this.state.currentPage === 1 ? 'disabled' : ''}>
            <i class="fas fa-angle-left"></i>
        </button>
    `).click(() => {
            dataTable.changePage(dataTable.state.currentPage - 1);
        });
        pagination.append(prevButton);

        // Page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, this.state.currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(this.state.totalPages, startPage + maxVisiblePages - 1);

        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        if (startPage > 1) {
            const firstPageButton = $(`<button class="${buttonStyle}">1</button>`)
                .click(() => dataTable.changePage(1));
            pagination.append(firstPageButton);

            if (startPage > 2) {
                pagination.append('<span class="mx-2">...</span>');
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageButton = $(`
            <button class="${this.state.currentPage === i ? activeButtonStyle : buttonStyle}">
                ${i}
            </button>
        `).click(() => dataTable.changePage(i));
            pagination.append(pageButton);
        }

        if (endPage < this.state.totalPages) {
            if (endPage < this.state.totalPages - 1) {
                pagination.append('<span class="mx-2">...</span>');
            }

            const lastPageButton = $(`<button class="${buttonStyle}">${this.state.totalPages}</button>`)
                .click(() => dataTable.changePage(this.state.totalPages));
            pagination.append(lastPageButton);
        }

        // Next button
        const nextButton = $(`
        <button class="${this.state.currentPage === this.state.totalPages ? disabledButtonStyle : buttonStyle}"
            ${this.state.currentPage === this.state.totalPages ? 'disabled' : ''}>
            <i class="fas fa-angle-right"></i>
        </button>
    `).click(() => {
            dataTable.changePage(dataTable.state.currentPage + 1);
        });
        pagination.append(nextButton);
    }
    changePage(page) {
        if (page < 1 || page > this.state.totalPages || page === this.state.currentPage) return;
        this.state.currentPage = page;
        this.loadData(this.additionalParams);
    }

    updateTableInfo() {
        const start = (this.state.currentPage - 1) * this.state.perPage + 1;
        const end = Math.min(this.state.currentPage * this.state.perPage, this.state.totalItems);
        $('#tableInfo').text(`Showing ${start} to ${end} of ${this.state.totalItems} entries`);
    }

    // Utility methods
    getLoadingRow() {
        return `
            <tr>
                <td colspan="100%" class="text-center py-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Loading data...</span>
                    </div>
                </td>
            </tr>
        `;
    }

    getErrorRow() {
        return `
            <tr>
                <td colspan="100%" class="text-center text-danger py-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error loading data. Please try again.
                </td>
            </tr>
        `;
    }

    getNoDataRow() {
        return `
            <tr>
                <td colspan="100%" class="text-center py-4">
                    <i class="fas fa-database me-2"></i>
                    No records found
                </td>
            </tr>
        `;
    }

    formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Common action methods
    confirmAction(config) {
        return Swal.fire({
            title: config.title || 'Are you sure?',
            text: config.text || "You won't be able to revert this!",
            icon: config.icon || 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: config.confirmText || 'Yes, proceed!',
            ...config
        });
    }

    showLoading(message = 'Processing...') {
        Swal.fire({
            title: message,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
    }

    showSuccess(message, title = 'Success!') {
        Swal.fire(title, message, 'success');
    }

    showError(message, title = 'Error!') {
        Swal.fire(title, message, 'error');
    }
    getSponsorDetails(sponsorDetails) {
        try {
            const details = JSON.parse(sponsorDetails || '{}');
            return this.getUserDetails(
                details.user_id,
                details.given_name,
                details.surname,
                details.email,
                details.email_confirmed,
                details.referral_details
            );
        } catch (e) {
            console.error('Error parsing sponsor details:', e);
            return null;
        }
    }

    capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    formatNumber(number, decimals) {
        return number.toLocaleString(undefined, {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }

    floorNumberFormat(number, decimals) {
        // Floor the number first (round down)
        const floored = Math.floor(number * Math.pow(10, decimals)) / Math.pow(10, decimals);
        // Then format with fixed decimals
        return floored.toLocaleString(undefined, {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }

    getUserDetails(userId, givenName, surname, email, emailConfirmed, referralDetails = null) {
        let switchUserButton = '';
        let userShowRoute = '';
        let userShowSponsor = '';
        let ref = '';

        userShowRoute = `/admin/customer/${userId}`;
        const switchUserRoute = `/admin/customer/${userId}/switch`;

        switchUserButton = `<span type='button' class='btnSwitchUser' data-toggle='tooltip'
            title='Switch User' data-url='${switchUserRoute}'>
            <i class='fas fa-exchange-alt'></i>
        </span>`;

        const verifiedIcon = `
        <span class="svg-icon svg-icon-muted svg-icon-1hx">
            <svg xmlns="https://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <path d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242..." fill="#00A3FF"></path>
                <path class="permanent" d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289..." fill="white"></path>
            </svg>
        </span>`;

        if (referralDetails) {
            try {
                const refData = typeof referralDetails === 'string' ? JSON.parse(referralDetails) : referralDetails;

                if (refData && refData.user_id) {
                    userShowSponsor = `/admin/customer/${refData.user_id}?ui=referral`;
                    ref = `
                    <span class="fw-bold text-primary">
                        <a target="_blank" href="${userShowSponsor}">
                            ${refData.given_name ? escapeHtml(refData.given_name) + ' ' + escapeHtml(refData.surname) : 'N/A'}
                        </a>
                    </span><br />
                    <span class="fw-semibold text-gray-400">${escapeHtml(refData.email)}</span>
                    ${refData.email_confirmed ? verifiedIcon : ''}
                `;
                }
            } catch (error) {
                console.error('Error parsing referral details:', error);
            }
        }

        return `
        <span class="fw-bold">
            <a target="_blank" href="${userShowRoute}">
                ${givenName ? escapeHtml(givenName) + ' ' + escapeHtml(surname) : 'N/A'}
            </a>
        </span>
        ${switchUserButton}<br/>
        <span class="fw-semibold text-gray-400">${escapeHtml(email)}</span>
        ${emailConfirmed ? verifiedIcon : ''}<br/>
        ${ref || ''}
    `;
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
