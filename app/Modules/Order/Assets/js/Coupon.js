class Coupon {
    constructor() {
        this.baseUrl = '/keuring-aanvragen';
        this.couponInput = document.querySelector('input[name="coupon_code"]');
        this.couponMessage = document.getElementById('coupon-message');
        this.grandTotalElement = document.getElementById('grandTotal');
        this.init();
    }

    init() {
        console.log('init');
        this.couponInput.addEventListener('input', (e) => this.handleInput(e));
    }

    async handleInput(event) {
        const couponCode = event.target.value;
        const originalTotal = parseFloat(this.grandTotalElement.dataset.originalTotal);

        if (couponCode.length === 8) {
            try {
                const result = await this.validateCoupon(couponCode, originalTotal);
                this.updateUI(result);
            } catch (error) {
                this.showError('Er is een fout opgetreden bij het valideren van de kortingscode.');
            }
        } else {
            if (couponCode.length === 0) {
                this.grandTotalElement.textContent = '€ ' + originalTotal.toFixed(2);
                this.clearMessage();
                document.getElementById('summary-discount').style.display = 'none';
            } else if (couponCode.length < 8) {
                this.showError('Kortingscode moet 8 tekens lang zijn');
            }
        }
    }

    async validateCoupon(couponCode, totalAmount) {
        const response = await fetch(`${this.baseUrl}/validate-coupon`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ coupon_code: couponCode })
        });

        if (!response.ok) {
            throw new Error('Netwerkrespons was niet in orde');
        }

        const data = await response.json();
        
        // Check if the response indicates a valid coupon
        if (data.valid) {
            let newTotal = totalAmount; // Start with the original total
            let discountLabel = '';

            // Convert discount_value to a number
            const discountValue = parseFloat(data.discount_value); // Ensure discount_value is a number

            // Calculate the new total based on discount type
            if (data.discount_type === 'percentage') {
                const discountAmount = (discountValue / 100) * totalAmount;
                newTotal = totalAmount - discountAmount;
                discountLabel = `Korting toegepast: ${discountValue}%`;
            } else if (data.discount_type === 'fixed') {
                newTotal = totalAmount - discountValue;
                discountLabel = `Korting toegepast: €${discountValue}`;
            }

            // Ensure newTotal is not NaN
            if (isNaN(newTotal)) {
                newTotal = totalAmount; // Reset to original total if calculation fails
            }

            // Return a structured response with the new total and discount label
            return {
                valid: true,
                new_total: newTotal,
                discount_label: discountLabel,
                message: data.message // Success message from the server
            };
        } else {
            // Return a structured response for an invalid coupon
            return {
                valid: false,
                message: data.message || 'Ongeldige kortingscode', // Fallback message
                errorCode: data.errorCode || 'INVALID_CODE' // Fallback error code
            };
        }
    }

    updateUI(response) {
        if (response.valid) {
            this.grandTotalElement.textContent = '€ ' + response.new_total.toFixed(2);
            this.couponMessage.textContent = response.discount_label;
            this.couponMessage.style.color = 'green';

            document.getElementById('summary-grandTotal').textContent = '€ ' + response.new_total.toFixed(2);
            document.getElementById('summary-discountLabel').textContent = response.discount_label;
            document.getElementById('summary-discount').style.display = 'block';
        } else {
            this.couponMessage.textContent = response.message;
            this.couponMessage.style.color = 'red';

            const originalTotal = parseFloat(this.grandTotalElement.dataset.originalTotal);
            this.grandTotalElement.textContent = '€ ' + originalTotal.toFixed(2);
            document.getElementById('summary-grandTotal').textContent = '€ ' + originalTotal.toFixed(2);
            document.getElementById('summary-discount').style.display = 'none';
        }
    }

    showError(message) {
        this.couponMessage.textContent = message;
        this.couponMessage.style.color = 'red';
    }

    clearMessage() {
        this.couponMessage.textContent = '';
    }
}

// Initialize the Coupon class when the document is ready
document.addEventListener('DOMContentLoaded', () => {
    new Coupon();
}); 