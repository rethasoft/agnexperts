class Cart {
    constructor() {
        this.cart = [];
        this.cartTableBody = document.querySelector('#cart-table tbody');
        this.cartDiv = document.getElementById('cart');
        this.step = 1;
        this.stepCount = 5;
        // Mevcut verileri yükle
        if (typeof existingCartData !== 'undefined') {
            console.log("Mevcut veriler yükleniyor:", existingCartData);
            existingCartData.forEach(detail => {
                this.cart.push({
                    id: detail.type_id,
                    category_id: detail.category_id,
                    name: detail.name,
                    quantity: detail.quantity,
                    price: detail.price,
                    total: detail.price * detail.quantity
                });

                // Tabloyu güncelle
                const row = document.createElement('tr');
                row.setAttribute('id', 'item-' + detail.type_id);
                row.classList.add('service-row');
                row.dataset.id = detail.type_id;

                row.innerHTML = `
                    <td><button type="button" class="btn btn-sm btn-danger" onclick="deleteCartProduct(${detail.type_id}, true)">X</button></td>
                    <td>${detail.type_id}</td>
                    <td>${detail.category ? detail.category.name + ' > ' + detail.name : detail.name}</td>
                    <td data-id="${detail.type_id}" class="data-quantity">
                        <input type="number" class="cart-counter" value="${detail.quantity}" 
                               onchange="changeQuantity(this)" style="width:60px;text-align:center;"/>
                    </td>
                    <td class="price">€${detail.price}</td>
                    <td data-id="${detail.type_id}" class="data-total text-end">€${(detail.price * detail.quantity)}</td>
                `;

                this.cartTableBody.appendChild(row);
            });

            // Cart'ta ürün varsa totalleri hesapla
            if (this.cart.length > 0) {
                this.cartDiv.classList.remove('d-none');
                this.calculateCartTotal();
                this.finish();
            }
        }

        this.exclTotal = 0;
        this.btw = 21;
        this.total = 0;

        // buttons
        this.previous = document.getElementById('previous-button');
        this.next = document.getElementById('next-button');

        // Request path
        this.requestPath = '/tenant/keuringen-detail/';
    }

    initializeFromExistingData(existingData) {
        // Önce tabloyu temizle
        this.cartTableBody.innerHTML = '';

        existingData.forEach(item => {
            // Cart'a ekle
            this.cart.push(item);

            // Tabloyu güncelle
            const row = document.createElement('tr');
            row.setAttribute('id', 'item-' + item.id);
            row.classList.add('service-row');
            row.dataset.id = item.id;

            row.innerHTML = `
                <td><button type="button" class="btn btn-sm btn-danger" onclick="deleteCartProduct(${item.id}, true)">X</button></td>
                <td>${item.id}</td>
                <td>${item.name}</td>
                <td data-id="${item.id}" class="data-quantity">
                    <input type="number" class="cart-counter" value="${item.quantity}" 
                           onchange="changeQuantity(this)" style="width:60px;text-align:center;"/>
                </td>
                <td class="price">€${item.price.toFixed(2)}</td>
                <td data-id="${item.id}" class="data-total text-end">€${(item.price * item.quantity).toFixed(2)}</td>
            `;

            this.cartTableBody.appendChild(row);
        });

        if (this.cart.length > 0) {
            this.cartDiv.classList.remove('d-none');
            this.calculateCartTotal();
            this.finish();
        }
    }

    add(product) {
        const row = document.createElement('tr');
        // Check if an item with the same id already exists in the cart
        const existingProductIndex = this.cart.findIndex(existingProduct => parseInt(existingProduct.id) === parseInt(product.id));

        // If it exists, update quantity and total
        if (existingProductIndex !== -1) {
            this.update(existingProductIndex, product.quantity);
        } else {
            // If it doesn't exist, add the new item to the cart
            this.cart.push(product);
            row.setAttribute('id', 'item-' + product.id);

            row.innerHTML = `
            <td><button type="button" class="btn btn-sm btn-danger" onclick="deleteCartProduct(${product.id}, true)">X</button></td>
            <td>${product.id}</td>
            <td>${product.category_name && product.category_name != '' ? product.category_name + ' > ' : ''} ${product.name}</td>
            <td data-id="${product.id}" class="data-quantity"><input type="number" class="cart-counter" value="${product.quantity}" onchange="changeQuantity(this)" style="width:60px;text-align:center;"/></td>
            <td>€${product.price}</td>
            <td data-id="${product.id}" class="data-total text-end">€${product.quantity * product.price}</td>
            `;
            this.cartTableBody.append(row);
        }

        // add hidden inputs
        this.finish();

        if (this.cart.length > 0) {
            this.cartDiv.classList.remove('d-none');
        }

        // get total
        this.calculateCartTotal();
    }

    update(index, additionalQuantity) {

        // Update quantity and total based on additional quantity
        this.cart[index].quantity += additionalQuantity;
        this.cart[index].total = this.calculateTotal(this.cart[index]);

        const _cart = this.cart[index];

        const findQuantity = this.cartTableBody.querySelector('td[data-id="' + _cart.id + '"].data-quantity input');
        const findTotal = this.cartTableBody.querySelector('td[data-id="' + _cart.id + '"].data-total');

        if (findTotal && findQuantity) {
            findQuantity.value = _cart.quantity;
            findTotal.innerHTML = '€' + _cart.total;
        }
        // log
        // console.log(`Quantity updated for item with id ${this.cart[index].id}. New total: ${this.cart[index].total}`);
    }

    async delete(id, preview = false) {
        const indexToDelete = this.cart.findIndex(product => parseInt(product.id) === parseInt(id));

        if (preview == false) {
            let response = await fetch(this.requestPath + id, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF_TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                // Handle non-successful response (e.g., show an error message)
                const errorData = await response.json();
                console.error('Error:', errorData.message);
                return;
            }
        }

        if (indexToDelete !== -1) {
            this.cart.splice(indexToDelete, 1);

            let getItem = document.getElementById(`item-${id}`);
            getItem.parentNode.removeChild(getItem);

            let cartInputs = document.querySelectorAll(`[name*="cart[${indexToDelete}]"`);

            cartInputs.forEach(cartInput => {
                cartInput.parentNode.removeChild(cartInput);
            });

            this.calculateCartTotal();
            return true;
        } else {
            return false;
        }
    }

    calculateTotal(product) {

        const existingProductIndex = this.cart.findIndex(item => parseInt(item.id) === parseInt(product.id));
        if (existingProductIndex !== -1) {
            if (product.extra == 1 && this.cart[existingProductIndex].quantity > 1) {
                console.log((product.price * 1));
                console.log(this.cart[existingProductIndex].quantity - 1 * product.extra_price);
                return (product.price * 1) + ((this.cart[existingProductIndex].quantity - 1) * product.extra_price);
            }
            return product.quantity * product.price;
        }
    }
    calculateCartTotal() {
        if (this.cart.length > 0) {
            let total = 0;
            this.cart.forEach((item) => {
                total += item.quantity * item.price;
            });

            this.exclTotal = total / 1.21;
            this.total = total;
            this.btw = this.total - this.exclTotal;

            // Ana toplam gösterimini güncelle
            document.getElementById('cart-total').textContent = 'Totaal: €' + this.total.toFixed(2);
        }
    }
    checkExistCart() {
        let existCarts = document.getElementById('existCarts');
        if (existCarts) {
            let data = JSON.parse(existCarts.value);
            if (data.length > 0) {
                data.forEach((item) => { this.cart.push(item) }); // add exist data to cart
            }
            this.finish();
        }
    }
    toNextStep() {

        this.finish();
        let validate = this.validateInputs();

        if (!validate) {
            if (this.step == 1) {
                alert('U moet ten minste één dienst selecteren');
            } else {
                alert('Verplichte velden moeten worden ingevuld.');
            }
            return false;
        }

        if (this.step < this.stepCount) {
            this.step++;

            this.previous.classList.remove('d-none');

            const steps = document.querySelectorAll('.steps');
            if (steps.length > 0) {
                steps.forEach((step) => step.classList.add('d-none'));
            }
            const nextStep = document.getElementById('step-' + this.step);
            nextStep.classList.remove('d-none');
        }

        if (this.step == 5) {
            $('#cart').removeClass('d-none');
        }
        if (this.step < 5) {
            $('#cart').addClass('d-none');
        }

    }
    toPreviousStep() {

        if (this.step > 1) {
            this.step--;
            const steps = document.querySelectorAll('.steps');
            if (steps.length > 0) {
                steps.forEach((step) => step.classList.add('d-none'));
            }
            const previousStep = document.getElementById('step-' + this.step);
            previousStep.classList.remove('d-none');
        }

        if (this.step == 1) {
            this.previous.classList.add('d-none');
        }


        if (this.step == 1) {
            $('#cart').removeClass('d-none');
        } else {
            $('#cart').addClass('d-none');
        }

    }

    finish() {
        let template = "";
        if (this.cart.length > 0) {
            $('.hidden-cart-inputs').remove();
            this.cart.forEach((item, index) => {
                template += `
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][type_id]" value="${item.id}" />
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][category_id]" value="${item.category_id}" />
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][name]" value="${item.name}" />
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][quantity]" value="${item.quantity}" />
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][price]" value="${item.price}" />
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][total]" value="${item.quantity * item.price}" />
                    `
            });
            $('#cart-form').append(template);
        }
        // $('#cart-form').submit();
    }
    validateInputs() {
        if (this.cart.length === 0) {
            return false;
        }

        const inputs = document.getElementById(`step-${this.step}`).querySelectorAll('input, select, textarea');

        for (const input of inputs) {
            // Check if the input has the required attribute
            const isRequired = input.hasAttribute('required');

            // Check if the input is required and empty
            if (isRequired && input.value.trim() === '') {
                return false; // Return false if any required input is empty
            }
        }

        return true; // All required inputs are filled
    }

}

// Define Cart class
const cart = new Cart();

// Default
cart.checkExistCart();
// Default end

document.addEventListener('DOMContentLoaded', function () {


    // buttons
    const addToCartSelect = document.querySelector('.add-to-cart-select');
    const addToCartButton = document.querySelectorAll('.add-to-cart');
    const deleteCartButton = document.querySelectorAll('.delete-cart');

    // SelectBox
    if (addToCartSelect) {
        addToCartSelect.onchange = function (element) {
            console.log(addToCartSelect);
            const selectedOption = element.target.options[element.target.selectedIndex];
            let dataProductValue = selectedOption.getAttribute('data-product');
            dataProductValue = JSON.parse(dataProductValue);
            cart.add(dataProductValue);

            addToCartSelect.value = 0;
        }
    }
    // SelectBox end

    if (addToCartButton) {
        addToCartButton.forEach((button) => {
            const product = JSON.parse(button.getAttribute('data-product'));

            button.addEventListener('click', function () {
                addToCartButton.forEach((oldButton) => oldButton.classList.remove('selected'));
                button.classList.add('selected');
                cart.add(product);
            });
        })
    }

    if (deleteCartButton) {
        deleteCartButton.forEach((button) => {
            const id = button.getAttribute('data-id');
            button.addEventListener('click', function () {
                if (confirm('Wilt u deze dienst verwijderen?')) {
                    cart.delete(id);
                }
            });
        })
    }

    // Quantity kontrollerini aktif et
    document.querySelectorAll('.cart-counter').forEach(input => {
        input.addEventListener('change', function () {
            changeQuantity(this);
        });
    });

});


// functions
function deleteCartProduct(id, preview) {
    const response = cart.delete(id, preview);

    if (response) {
        const row = cart.cartTableBody.querySelector('#item-' + id);
        if (row) {
            row.remove();
        }

    }
}
function toNextStep() {
    cart.toNextStep();
}

function toPreviousStep() {
    cart.toPreviousStep();
}

function getSubDienst(category_id, element) {
    // class events
    $('.step-box').removeClass('active');
    $(element).addClass('active');
    let subs = $('#subs');
    subs.empty();
    $.ajax({
        type: 'get',
        url: '/ajax/getSubDienst/' + category_id,
        success: function (response) {
            if (response.length > 0) {
                for (var i = 0; i < response.length; i++) {
                    let item = response[i];
                    let template = `
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="step-box border px-2 py-4 text-center sub-step-box" onclick="addToCart(this, {id:${item.id}, category_id: ${item.category_id}, title:'${item.title}', quantity:1, price:'${item.price}'})">
                            <div class="step-box-image">
                                ${item.icon}
                            </div>
                            <div class="step-box-title mt-2">
                                ${item.title}
                            </div>
                        </div>
                    </div>
                `;
                    subs.append(template);
                }
            }
        }
    });
}

function addToCart(element, { id, category_id, title, quantity, price }) {
    $('.sub-step-box').removeClass('active');
    $(element).addClass('active');

    cart.add({ id: id, category_id: category_id, title: title, quantity: quantity, price: price });
}

// Assuming the changeQuantity function looks like this:
function changeQuantity(inputElement) {
    const productId = inputElement.closest('td').getAttribute('data-id');
    const newQuantity = parseInt(inputElement.value, 10);

    // Find the product in the cart
    const existingProductIndex = cart.cart.findIndex(existingProduct => parseInt(existingProduct.id) === parseInt(productId));

    if (existingProductIndex !== -1) {
        // Update the quantity in the cart
        cart.cart[existingProductIndex].quantity = newQuantity;

        // Get the price and calculate new total
        const price = cart.cart[existingProductIndex].price;
        const newTotal = price * newQuantity;

        // Update the total in the cart
        cart.cart[existingProductIndex].total = newTotal;

        // Update the corresponding HTML elements
        const totalCell = inputElement.closest('tr').querySelector('.data-total');
        if (totalCell) {
            totalCell.textContent = '€' + newTotal.toFixed(2);
        }

        // Update hidden inputs
        cart.finish();

        // Update the overall cart total
        cart.calculateCartTotal();
    }
}


function isValidFileType(file) {
    // Define allowed file types
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
    return allowedTypes.includes(file.type);
}

function uploadPreview(event) {
    const input = event.target;
    const preview = document.getElementById('filesPreview');

    // Clear previous previews
    preview.innerHTML = '';

    if (input.files && input.files.length > 0) {
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            if (isValidFileType(file)) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    // Create an image element
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100%';
                    img.style.maxHeight = '74px'; // You can adjust the maximum height as needed

                    // Check if the file is a PDF
                    if (file.type === 'application/pdf') {
                        // If it's a PDF, use a default image
                        img.src = '/img/pdf-icon.png'; // Replace with your actual image path
                    }

                    // Add multiple classes to the img element
                    img.className = 'border px-1 py-1 me-2 rounded';

                    // Append the image to the preview div
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else {
                // Display an error message for invalid file types
                alert('Invalid file type. Please select only jpg, jpeg, png, or pdf files.');
            }

        }

        $('#filesPreview p').addClass('d-none');
    }
}
