// script.js
/*document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.navbar a');

    links.forEach(link => {
        link.addEventListener('click', () => {
            links.forEach(item => item.classList.remove('active'));
            link.classList.add('active');
        });
    });
});*/

/*var cartItemsContainer;
if (document.readyState == 'loading') {
    document.addEventListener('DOMContentLoaded', ready)
} else {
    ready()
}

function ready() {

    cartItemsContainer = document.querySelector('.cart-items');//maybe
    
    var removeCartItemButtons = document.getElementsByClassName('delete-btn')
    for (var i = 0; i < removeCartItemButtons.length; i++) {
        var button = removeCartItemButtons[i]
        button.addEventListener('click', removeCartItem)
    }

    var quantityInput = document.getElementsByClassName('cart-quantity')
    for (var i = 0; i < quantityInput.length; i++) {
        var input = quantityInput[i]
        input.addEventListener('change', quantityChanged)
    }

    var addToCartButtons = document.getElementsByClassName('add-to-cart')
    for (var i = 0; i < addToCartButtons.length; i++) {
        var button = addToCartButtons[i]
        button.addEventListener('click', addToCartClicked)
    }

}


function removeCartItem(event){
    var buttonClicked = event.target
    buttonClicked.parentElement.parentElement.parentElement.remove()
    updateCartTotal()
}

function quantityChanged(event) {
    var input = event.target
    if (isNaN(input.value) || input.value <= 0) {
        input.value = 1
    }
    updateCartTotal()
}

function addToCartClicked(event) {
    var button = event.target
    var shopItem = button.parentElement.parentElement.parentElement.parentElement.parentElement
    var title = shopItem.getElementsByClassName('card-name')[0].innerText
    var price = shopItem.getElementsByClassName('card-price')[0].innerText
    var imageSrc = shopItem.getElementsByClassName('card-img')[0].src
    console.log(price)
    console.log(imageSrc, title, price)
    addItemToCart(imageSrc, title, price)
    updateCartTotal()
}



function addItemToCart(imageSrc, title, price) {

    if(!cartItemsContainer) {
        cartItemsContainer = document.querySelector('.cart-items')
    }

    console.log(cartItemsContainer)
    
    var cartRow = document.createElement('tr');
    cartRow.classList.add('cart-row');
    console.log(cartRow);
   
    var cartItemNames = cartItemsContainer.getElementsByClassName('cart-title')[0]
    console.log(cartItemNames)
    for (var i = 0; i < cartItemNames.length; i++) {
        if (cartItemNames[i].innerText == title) {
            alert('This item is already added to the cart')
            return
         }
        }
    var cartRowContents = `
    <td><button class="delete-btn"><i class="fa-solid fa-trash"></i></button></td>
    <td><img src="${imageSrc}" alt="" class="cart-img"></td>
    <td class="cart-title">${title}</td>
    <td >${price}</td>
    <td><input type="number" value="1" class="cart-quantity"></td>
    <td class="cart-price">${price}</td>`
    /*var cartRowContents = `
    <div class="cart-item cart-column">
        <img src="${imageSrc}" alt="" class="cart-img">
        <span class="cart-title">${title}</span>
    </div>
    <span class="cart-price cart-column">${price}</span>
    <div class="cart-quantity cart-column">
        <input type="number" value="1" class="cart-quantity">
        <button class="delete-btn"><i class="fa-solid fa-trash"></i></button>
    </div>`*/

    //cartRow.innerHTML=cartRowContents
    //console.log(cartRow)
    //cartItemsContainer.append(cartRow)
    //cartRow.classList.add('cart-row')
    //var cartItems = document.getElementsByClassName('cart-items')[0]
    //cartItems.append(cartRow)
   // console.log(cartItems)
    //var cartItemNames = cartItems.getElementsByClassName('cart-title')
//console.log(cartItemNames)
    //for (var i = 0; i < cartItemNames.length; i++) {
      //  if (cartItemNames[i].innerText == title) {
        //    alert('This item is already added to the cart')
          //  return
      //  }
    //}
    /*var cartRowContents = `
    <td><button class="delete-btn"><i class="fa-solid fa-trash"></i></button></td>
    <td><img src="${imageSrc}" alt="" class="cart-img"></td>
    <td class="cart-title">${title}</td>
    <td >${price}</td>
    <td><input type="number" value="1" class="cart-quantity"></td>
    <td class="cart-price">${price}</td>`*/
    //cartRow.innerHTML = cartRowContents
    //cartItems.append(cartRow)
   /*cartRow.getElementsByClassName('delete-btn')[0].addEventListener('click', removeCartItem)
    cartRow.getElementsByClassName('cart-quantity')[0].addEventListener('change', quantityChanged)
}




    

    function updateCartTotal() {
        console.log(cartItemsContainer)
        //var cartItemContainer = document.getElementsByClassName('cart-items')[0]
        var cartRows = cartItemsContainer.getElementsByClassName('cart-row')
        var total = 0
        //var subtotal= 0
        for (var i = 0; i < cartRows.length; i++) {
            var cartRow = cartRows[i]
            var priceElement = cartRow.getElementsByClassName('cart-price')[0]
            var quantityElement = cartRow.getElementsByClassName('cart-quantity')[0]
            var price = parseFloat(priceElement.innerText.replace('R', ''))
            var quantity = quantityElement.value
            total = total + (price * quantity)
            console.log(total)
            
            //this isn't working subtotal= price * quantity
           
        }
        //this isn't workingdocument.getElementsByClassName('item*q')[0].innerText = "R" + subtotal
        total = Math.round(total * 100) / 100
        document.getElementsByClassName('cart-total-price')[0].innerText = "R" + total


        var Promoprice = 0
        document.getElementById('apply').addEventListener('click', function () {
            
            var code = document.getElementById('PromoId').value
            if (code === 'NewBuyer10'){
                Promoprice = 150
            }else if (code === 'Fifth05'){
                Promoprice = 200
            }else {
                Promoprice = 0
            }

            console.log(Promoprice)
            document.getElementsByClassName('promoCode')[0].innerText = "R" + Promoprice

            var ShippingCost 
            if (total > 999 || total == 0)  {
                ShippingCost = 0
            }else{
                ShippingCost = 100
            }

            console.log(ShippingCost)
            document.getElementsByClassName('SC')[0].innerText = "R" + ShippingCost
        

            var TT = 0

            TT = total - Promoprice + ShippingCost
            console.log(TT)
         document.getElementsByClassName('TTotal')[0].innerText = "R" + TT
            });
        

        

        
        //var Promoprice = parseFloat(document.getElementsByClassName('promoCode')[0].innerText.replace('R', ''))
        //var Pprice = parseFloat(Promoprice.innerText.replace('R', ''))
       

        var ShippingCost 
        if (total > 999 ) {
            ShippingCost = 0
        }else{
            ShippingCost = 100
        }

        console.log(ShippingCost)
        document.getElementsByClassName('SC')[0].innerText = "R" + ShippingCost
        

        var TT = 0

        TT = total - Promoprice + ShippingCost
        console.log(TT)
        document.getElementsByClassName('TTotal')[0].innerText = "R" + TT


        
    }*/
    //document.addEventListener("DOMContentLoaded", function() {
     //   const toggler = document.getElementById("navbar-toggler");
      //  const navbar = document.querySelector(".navbar");

       // toggler.addEventListener("click", function() {
       //     navbar.classList.toggle("active");
      //      toggler.classList.toggle("active");
     //   });

        // Optional: Close the navbar if you click outside of it
     //   document.addEventListener("click", function(event) {
         //   if (!toggler.contains(event.target) && !navbar.contains(event.target)) {
       //         navbar.classList.remove("active");
            //    toggler.classList.remove("active");
          //  }
       // });
   // }); 

  


    


    
