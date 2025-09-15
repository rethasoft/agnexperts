class Cart {
    constructor() {
        this.cart = [];
        this.cartTableBody = document.querySelector('#cart-table tbody');
        this.cartDiv = document.getElementById('cart');
        this.step = 1;
        this.stepCount = 5;
        // Mevcut verileri yükle
        if (typeof existingCartData !== 'undefined') {
            console.log(existingCartData);
            existingCartData.forEach(detail => {
                this.cart.push({
                    id: detail.type_id,
                    category_id: detail.category_id,
                    name: detail.name,
                    quantity: detail.quantity,
                    price: detail.price,
                    total: detail.price * detail.quantity,
                    is_offerte: detail.is_offerte || false
                });

                // Tabloyu güncelle
                const row = document.createElement('tr');
                row.setAttribute('id', 'item-' + detail.type_id);
                row.classList.add('service-row');
                row.dataset.id = detail.type_id;

                row.innerHTML = `
                    <td><button type="button" class="btn btn-sm btn-danger" onclick="deleteCartProduct(${detail.type_id}, true)">X</button></td>
                    <td>${detail.type_id}</td>
                    <td>${detail.category ? detail.category.name + ' > ' + detail.name : detail.name}${detail.is_offerte ? '<br><small class="text-info">Offerte - Prijs wordt opgegeven</small>' : ''}</td>
                    <td data-id="${detail.type_id}" class="data-quantity">
                        <input type="number" class="cart-counter" value="${detail.quantity}" min="1"
                               onchange="changeQuantity(this)" style="width:60px;text-align:center;"/>
                    </td>
                    <td class="price">${detail.is_offerte ? 'Offerte' : '€' + detail.price}</td>
                    <td data-id="${detail.type_id}" class="data-total text-end">${detail.is_offerte ? 'Offerte' : '€' + (detail.price * detail.quantity)}</td>
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
        
        // Combi discount özellikleri
        this.combiDiscount = null;
        this.combiDiscountId = null;
        this.combiDiscountType = null;
        this.combiDiscountValue = null;
        this.combiDiscountAmount = null;
        
        // Edit sayfasında mevcut combi discount verilerini yükle
        if (typeof existingCombiDiscount !== 'undefined') {
            this.combiDiscount = existingCombiDiscount;
            this.combiDiscountId = existingCombiDiscount.combi_discount_id;
            this.combiDiscountType = existingCombiDiscount.combi_discount_type;
            this.combiDiscountValue = existingCombiDiscount.combi_discount_value;
            this.combiDiscountAmount = existingCombiDiscount.combi_discount_amount;
        }

        // buttons
        this.previous = document.getElementById('previous-button');
        this.next = document.getElementById('next-button');

        // Request path
        this.requestPath = '/tenant/keuringen-detail/';
    }

    initializeFromExistingData(existingData) {
        
        // initializeFromExistingData çağrıldı
        
        // Önce tabloyu temizle
        this.cartTableBody.innerHTML = '';

        this.cart = [];
        
        // Combi discount verilerini yeniden yükle (cart temizlendiği için)
        if (typeof existingCombiDiscount !== 'undefined' && existingCombiDiscount !== null) {
            // existingCombiDiscount tanımlı, yükleniyor
            this.combiDiscount = existingCombiDiscount;
            this.combiDiscountId = existingCombiDiscount.combi_discount_id;
            this.combiDiscountType = existingCombiDiscount.combi_discount_type;
            this.combiDiscountValue = existingCombiDiscount.combi_discount_value;
            this.combiDiscountAmount = existingCombiDiscount.combi_discount_amount;
            
            console.log('🎉 Combi discount yüklendi:', this.combiDiscount);
        } else {
            // existingCombiDiscount tanımlı değil
            console.log('❌ existingCombiDiscount bulunamadı');
        }
        
        existingData.forEach(item => {
            // Cart'a ekle
            this.cart.push(item);

            // Tabloyu güncelle
            const row = document.createElement('tr');
            row.setAttribute('id', 'item-' + item.id);
            row.classList.add('service-row');
            row.dataset.id = item.id;

            // Offerte servislerde total "Offerte", normal servislerde mevcut total kullan
            let displayTotal;
            if (item.is_offerte) {
                displayTotal = 'Offerte';
                // Offerte item için total "Offerte" olarak ayarlandı
            } else {
                // item.total veya (item.price * item.quantity) undefined olabilir, kontrol et
                const totalValue = item.total || (item.price * item.quantity);
                if (totalValue !== undefined && totalValue !== null && !isNaN(totalValue)) {
                    displayTotal = '€' + parseFloat(totalValue).toFixed(2);
                } else {
                    console.warn('⚠️ Item total hesaplanamadı:', item);
                    displayTotal = '€0.00';
                }
            }

            row.innerHTML = `
                <td><button type="button" class="btn btn-sm btn-danger" onclick="deleteCartProduct(${item.id}, true)">X</button></td>
                <td>${item.id}</td>
                <td>${item.name}${item.is_offerte ? '<br><small class="text-info">Offerte - Prijs wordt opgegeven</small>' : ''}</td>
                <td data-id="${item.id}" class="data-quantity">
                    <input type="number" class="cart-counter" value="${item.quantity}" min="1"
                           onchange="changeQuantity(this)" style="width:60px;text-align-center;" ${item.is_offerte ? 'disabled' : ''}/>
                </td>
                <td class="price">${item.is_offerte ? 'Offerte' : '€' + (item.price !== undefined && item.price !== null && !isNaN(item.price) ? parseFloat(item.price).toFixed(2) : '0.00')}</td>
                <td data-id="${item.id}" class="data-total text-end">${displayTotal}</td>
            `;

            this.cartTableBody.appendChild(row);
        });

        if (this.cart.length > 0) {
            this.cartDiv.classList.remove('d-none');
            this.calculateCartTotal();
            this.finish();
            
            // Combi discount kontrolü yap
            // Combi discount kontrolü yapılıyor
            this.checkCombiDiscountAfterCartChange();
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

            // Calculate initial total with extra price logic
            let initialTotal;
            if (product.is_offerte) {
                // Offerte servislerde total 0
                initialTotal = 0;
            } else if ((product.extra == 1 || product.extra === true) && product.quantity > 1 && product.extra_price) {
                // İlk ürün normal fiyat, sonraki ürünler extra fiyat
                // product.price ve product.extra_price string olabilir, parseFloat ile sayıya çevir
                const price = parseFloat(product.price) || 0;
                const extraPrice = parseFloat(product.extra_price) || 0;
                const quantity = parseInt(product.quantity) || 1;
                initialTotal = (price * 1) + ((quantity - 1) * extraPrice);
            } else {
                // Normal fiyat hesaplaması
                // product.price string olabilir, parseFloat ile sayıya çevir
                const price = parseFloat(product.price) || 0;
                const quantity = parseInt(product.quantity) || 1;
                initialTotal = quantity * price;
            }

            // Set the total in the product object
            // product.total = initialTotal;
            product.total = initialTotal;
            


            row.innerHTML = `
            <td><button type="button" class="btn btn-sm btn-danger" onclick="deleteCartProduct(${product.id}, true)">X</button></td>
            <td>${product.id}</td>
            <td>${product.category_name && product.category_name != '' ? product.category_name + ' > ' : ''} ${product.name}${product.is_offerte ? '<br><small class="text-info">Offerte - Prijs wordt opgegeven</small>' : ''}</td>
            <td data-id="${product.id}" class="data-quantity"><input type="number" class="cart-counter" value="${product.quantity}" min="1" onchange="changeQuantity(this)" style="width:60px;text-align-center;"/></td>
            <td>${product.is_offerte ? 'Offerte' : '€' + (product.price !== undefined && product.price !== null ? parseFloat(product.price).toFixed(2) : '0.00')}</td>
            <td data-id="${product.id}" class="data-total text-end">${product.is_offerte ? 'Offerte' : '€' + (initialTotal !== undefined && initialTotal !== null ? parseFloat(initialTotal).toFixed(2) : '0.00')}</td>
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
        
        // Combi discount kontrolü yap
        this.checkCombiDiscountAfterCartChange();
    }

    update(index, additionalQuantity) {

        // Update quantity and total based on additional quantity
        this.cart[index].quantity += additionalQuantity;
        
        // Minimum değer kontrolü
        if (this.cart[index].quantity < 1) {
            this.cart[index].quantity = 1;
        }
        
        this.cart[index].total = this.calculateTotal(this.cart[index]);

        const _cart = this.cart[index];

        const findQuantity = this.cartTableBody.querySelector('td[data-id="' + _cart.id + '"].data-quantity input');
        const findTotal = this.cartTableBody.querySelector('td[data-id="' + _cart.id + '"].data-total');

        if (findTotal && findQuantity) {
            findQuantity.value = _cart.quantity;
            if (_cart.is_offerte) {
                findTotal.innerHTML = 'Offerte';
            } else {
                // _cart.total undefined olabilir, kontrol et
                if (_cart.total !== undefined && _cart.total !== null) {
                    findTotal.innerHTML = '€' + parseFloat(_cart.total).toFixed(2);
                } else {
                    findTotal.innerHTML = '€0.00';
                }
            }
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
            
            // Combi discount kontrolü yap
            this.checkCombiDiscountAfterCartChange();
            
            return true;
        } else {
            return false;
        }
    }

    calculateTotal(product) {
        // If product is offerte, return 0
        if (product.is_offerte) {
            return 0;
        }
        
        // product.price undefined olabilir, kontrol et
        if (product.price === undefined || product.price === null) {
            console.warn('⚠️ Product price undefined:', product);
            return 0;
        }
        
        const existingProductIndex = this.cart.findIndex(item => parseInt(item.id) === parseInt(product.id));
        if (existingProductIndex !== -1) {
            if ((product.extra == 1 || product.extra === true) && this.cart[existingProductIndex].quantity > 1 && product.extra_price) {
                // İlk ürün normal fiyat, sonraki ürünler extra fiyat
                return (product.price * 1) + ((this.cart[existingProductIndex].quantity - 1) * product.extra_price);
            }
            return product.quantity * product.price;
        }
        return product.quantity * product.price;
    }

    calculateCartTotal() {
        console.log(this.cart);
        if (this.cart.length > 0) {
            let total = 0;
            this.cart.forEach((item) => {
                // If item is offerte, don't add to total
                if (!item.is_offerte) {
                    // Her satırın kendi total değerini kullan
                    // Bu değer zaten extra fiyat mantığına göre hesaplanmış
                    if (item.total !== undefined && item.total !== null) {
                        // item.total string olabilir, parseFloat ile sayıya çevir
                        total += parseFloat(item.total) || 0;
                    } else if (item.price !== undefined && item.price !== null && item.quantity !== undefined && item.quantity !== null) {
                        // item.price ve item.quantity string olabilir, parseFloat ile sayıya çevir
                        total += (parseFloat(item.price) || 0) * (parseFloat(item.quantity) || 0);
                    } else {
                        console.warn('⚠️ Item has missing price or quantity:', item);
                    }
                }
            });

            this.exclTotal = total / 1.21;
            this.total = total;
            this.btw = this.total - this.exclTotal;
        

            // Combi discount varsa uygula
            if (this.combiDiscount && this.combiDiscount.has_combi) {
                let discountedTotal = this.total;
                if (this.combiDiscount.discount_type === 'percentage') {
                    discountedTotal = this.total * (1 - this.combiDiscount.discount_value / 100);
                } else {
                    discountedTotal = this.total - this.combiDiscount.discount_value;
                }
                if (discountedTotal < 0) discountedTotal = 0;
                
                // Combi discount değerlerini kaydet!
                this.combiDiscountId = this.combiDiscount.combi_id;
                this.combiDiscountType = this.combiDiscount.discount_type;
                this.combiDiscountValue = this.combiDiscount.discount_value;
                this.combiDiscountAmount = (this.total - discountedTotal).toFixed(2);
                
                // Ana toplam gösterimini güncelle (combi discount ile)
                this.updateCartTotalDisplay(discountedTotal, true);
            } else {
                // Ana toplam gösterimini güncelle (normal)
                this.updateCartTotalDisplay(this.total, false);
            }
        }
    }
    
    updateCartTotalDisplay(total, hasCombiDiscount = false) {
        const cartTotalElement = document.getElementById('cart-total');
        if (!cartTotalElement) return;
        
        if (hasCombiDiscount) {
            let html = '<div class="text-end">';
            html += '<div><span class="fw-normal">Normale prijs:</span> <span class="text-decoration-line-through text-danger">€' + (this.total !== undefined && this.total !== null ? parseFloat(this.total).toFixed(2) : '0.00') + '</span></div>';
            html += '<div><span class="fw-normal">Combi-korting:</span> <span class="text-success">';
            if (this.combiDiscount.discount_type === 'percentage') {
                html += this.combiDiscount.discount_value + '%';
            } else {
                html += '€' + (this.combiDiscount.discount_value !== undefined && this.combiDiscount.discount_value !== null ? parseFloat(this.combiDiscount.discount_value).toFixed(2) : '0.00');
            }
            html += ' korting</span></div>';
            html += '<div><span class="fw-normal">Totaal:</span> <span>€' + (total !== undefined && total !== null ? parseFloat(total).toFixed(2) : '0.00') + '</span></div>';
            html += '</div>';
            cartTotalElement.innerHTML = html;
        } else {
            cartTotalElement.innerHTML = '<span class="fw-bold">Totaal: €' + (total !== undefined && total !== null ? parseFloat(total).toFixed(2) : '0.00') + '</span>';
        }
    }
    
    checkCombiDiscount(selectedServiceIds) {
        
        console.log('🔍 checkCombiDiscount çağrıldı, selectedServiceIds:', selectedServiceIds);
        
        if (selectedServiceIds.length < 2) {
            // Eğer mevcut combi discount varsa ve edit sayfasındaysak, silme
            if (this.combiDiscount && this.combiDiscount.has_combi && typeof existingCombiDiscount !== 'undefined') {
                // Edit sayfasında mevcut combi discount korunuyor
                return;
            }
            
            console.log('🗑️ Combi discount temizleniyor');
            this.combiDiscount = null;
            this.combiDiscountId = null;
            this.combiDiscountType = null;
            this.combiDiscountValue = null;
            this.combiDiscountAmount = null;
            this.calculateCartTotal();
            return;
        }
        
        // Mevcut combi discount varsa ve edit sayfasındaysak, yeni AJAX yapma
        if (this.combiDiscount && this.combiDiscount.has_combi) {
            // Edit sayfasında mevcut combi discount korunuyor, AJAX yapılmıyor
            // Mevcut combi discount ile toplam hesapla
            this.calculateCartTotal();
            return;
        }
        
        // AJAX ile combi discount kontrol ediliyor
        console.log('📡 AJAX ile combi discount kontrol ediliyor...');
        $.ajax({
            url: '/app/tenant/ajax/check-combi-discount',
            type: 'POST',
            data: {
                service_ids: selectedServiceIds,
                _token: document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
            success: (response) => {
                // AJAX yanıtı alındı
                this.combiDiscount = response;
                if (response.has_combi) {
                    // Combi discount bulundu
                    this.combiDiscountId = response.combi_id;
                    this.combiDiscountType = response.discount_type;
                    this.combiDiscountValue = response.discount_value;
                    
                    // Toplamdan indirim miktarını hesapla
                    let discountAmount = 0;
                    if (response.discount_type === 'percentage') {
                        discountAmount = this.total * (parseFloat(response.discount_value) / 100);
                    } else {
                        discountAmount = parseFloat(response.discount_value);
                    }
                    this.combiDiscountAmount = discountAmount.toFixed(2);
                    
                    // İndirim miktarı hesaplandı
                    
                    // Combi discount mesajını göster
                    this.showCombiDiscountMessage(response);
                } else {
                    // Combi discount bulunamadı
                    this.combiDiscountId = null;
                    this.combiDiscountType = null;
                    this.combiDiscountValue = null;
                    this.combiDiscountAmount = null;
                }
                this.calculateCartTotal();
            },
            error: (xhr, status, error) => {
                console.error('❌ AJAX hatası:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        });
    }
    
    showCombiDiscountMessage(response) {
        // Eski mesajı kaldır
        const existingMessage = document.getElementById('combi-discount-info');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Yeni mesaj ekle
        const typesContainer = document.querySelector('#types').closest('.card-body');
        if (typesContainer) {
            let msg = '<div id="combi-discount-info" class="alert alert-success mt-3">';
            msg += '<strong>Combi-korting toegepast!</strong> ';
            if (response.discount_type === 'percentage') {
                msg += 'Er wordt <b>' + response.discount_value + '%</b> korting toegepast op het totaal.';
            } else {
                msg += 'Er wordt <b>€' + response.discount_value + '</b> korting toegepast op het totaal.';
            }
            msg += '</div>';
            typesContainer.insertAdjacentHTML('beforeend', msg);
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
                // Offerte servislerde total 0, normal servislerde mevcut total kullan
                let itemTotal = item.is_offerte ? 0 : (item.total || (item.quantity * item.price));
                
                // itemTotal undefined olabilir, kontrol et
                if (itemTotal === undefined || itemTotal === null) {
                    console.warn('⚠️ ItemTotal undefined:', item);
                    itemTotal = 0;
                }

                template += `
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][type_id]" value="${item.id}" />
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][category_id]" value="${item.category_id}" />
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][name]" value="${item.name}" />
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][category_name]" value="${item.category_name || ''}" />
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][quantity]" value="${item.quantity}" />
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][price]" value="${item.price}" />
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][total]" value="${parseFloat(itemTotal).toFixed(2)}" />
                        <input class="hidden-cart-inputs" type="hidden" name="items[${index}][is_offerte]" value="${item.is_offerte ? '1' : '0'}" />
                    `
            });
            // Combi discount hidden input'larını ekle
            console.log('🔍 Combi discount değerleri:', {
                combiDiscountId: this.combiDiscountId,
                combiDiscountType: this.combiDiscountType,
                combiDiscountValue: this.combiDiscountValue,
                combiDiscountAmount: this.combiDiscountAmount
            });
            
            if (this.combiDiscountId && this.combiDiscountType) {
                console.log('✅ Combi discount hidden input\'lar ekleniyor...');
                template += `
                    <input class="hidden-cart-inputs" type="hidden" name="combi_discount_id" value="${this.combiDiscountId}" />
                    <input class="hidden-cart-inputs" type="hidden" name="combi_discount_type" value="${this.combiDiscountType}" />
                    <input class="hidden-cart-inputs" type="hidden" name="combi_discount_value" value="${this.combiDiscountValue || ''}" />
                    <input class="hidden-cart-inputs" type="hidden" name="combi_discount_amount" value="${this.combiDiscountAmount || ''}" />
                `;
            } else {
                console.log('❌ Combi discount değerleri eksik!');
            }
            
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

    // Cart değişikliklerinden sonra combi discount kontrolü
    checkCombiDiscountAfterCartChange() {
        
        // checkCombiDiscountAfterCartChange çağrıldı
        
        if (this.cart.length >= 2) {
            // 2+ servis var, combi discount kontrol ediliyor
            const serviceIds = this.cart.map(item => item.id.toString());
            // Service IDs alındı
            
            // Mevcut combi discount varsa ve edit sayfasındaysak, yeni hesaplama yapma
            if (this.combiDiscount && this.combiDiscount.has_combi) {
                // Mevcut combi discount korunuyor
                // Mevcut combi discount ile toplam hesapla
                this.calculateCartTotal();
                return;
            }
            
            // Yeni combi discount kontrolü
            this.checkCombiDiscount(serviceIds);
        } else {
            // 2'den az servis var, combi discount temizleniyor
            // 2'den az servis varsa combi discount'ı temizle
            this.combiDiscount = null;
            this.combiDiscountId = null;
            this.combiDiscountType = null;
            this.combiDiscountValue = null;
            this.combiDiscountAmount = null;
            this.calculateCartTotal();
        }
    }
}

// Define Cart class
const cart = new Cart();

// Default
cart.checkExistCart();

// Combi discount event listener'ını ekle
document.addEventListener('DOMContentLoaded', function() {
    const typesSelect = document.querySelector('#types');
    if (typesSelect) {
        typesSelect.addEventListener('change', function() {
            const selected = Array.from(this.selectedOptions).map(option => option.value).filter(id => id !== "0" && id !== "");
            cart.checkCombiDiscount(selected);
        });
    }
});

// Default end

document.addEventListener('DOMContentLoaded', function () {


    // buttons
    const addToCartSelect = document.querySelector('.add-to-cart-select');
    const addToCartButton = document.querySelectorAll('.add-to-cart');
    const deleteCartButton = document.querySelectorAll('.delete-cart');

    // SelectBox
    if (addToCartSelect) {
        addToCartSelect.onchange = function (event) {
            // Çoklu seçim desteği (select2 veya native multiple)
            const selectedOptions = Array.from(addToCartSelect.selectedOptions || []);
            selectedOptions.forEach(option => {
                // Sadece geçerli (0/boş olmayan ve data-product'ı olan) option'lar işlenir
                const dataProductValue = option.getAttribute('data-product');
                if (!dataProductValue) return;
                let product = null;
                try {
                    product = JSON.parse(dataProductValue);
                } catch (e) {
                    return;
                }
                if (!product || !product.id) return;
                // Aynı ürün cart'ta zaten varsa tekrar ekleme
                if (cart.cart.find(item => parseInt(item.id) === parseInt(product.id))) return;
                cart.add(product);
            });
            // Seçimi sıfırlama kaldırıldı, sonsuz döngüye sebep oluyordu
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
        
        // Input'a minimum değer kontrolü ekle
        input.addEventListener('input', function() {
            if (this.value < 1) {
                this.value = 1;
            }
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
    let newQuantity = parseInt(inputElement.value, 10);

    // Minimum değer kontrolü
    if (newQuantity < 1) {
        newQuantity = 1;
        inputElement.value = 1;
    }

    // Find the product in the cart
    const existingProductIndex = cart.cart.findIndex(existingProduct => parseInt(existingProduct.id) === parseInt(productId));


    if (existingProductIndex !== -1) {

        if ( cart.cart[existingProductIndex].is_offerte ) {
            inputElement.value = 1;
            return;
        }
        // Update the quantity in the cart
        cart.cart[existingProductIndex].quantity = newQuantity;

        // Calculate new total with extra price logic
        const item = cart.cart[existingProductIndex];
        let newTotal;
        
        // item.price undefined olabilir, kontrol et
        if (item.price === undefined || item.price === null) {
            console.warn('⚠️ Item price undefined:', item);
            return;
        }
        
        if ((item.extra == 1 || item.extra === true) && newQuantity > 1 && item.extra_price) {
            // İlk ürün normal fiyat, sonraki ürünler extra fiyat
            newTotal = (item.price * 1) + ((newQuantity - 1) * item.extra_price);
        } else {
            // Normal fiyat hesaplaması
            newTotal = newQuantity * item.price;
        }

        // Update the total in the cart
        cart.cart[existingProductIndex].total = newTotal;

        // Update the corresponding HTML elements
        const totalCell = inputElement.closest('tr').querySelector('.data-total');
        if (totalCell) {
            totalCell.textContent = '€' + (newTotal !== undefined && newTotal !== null ? parseFloat(newTotal).toFixed(2) : '0.00');
        }

        // Update hidden inputs
        cart.finish();

        // Update the overall cart total
        cart.calculateCartTotal();
        
        // Combi discount kontrolü yap
        cart.checkCombiDiscountAfterCartChange();
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
