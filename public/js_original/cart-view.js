/**
 * This script is executed on /cart page. It allows users to modify their cart in real-time and view the updated totals.
 */

import Cart from "./models/Cart";
import CartItem from "./models/CartItem";
import ModalManager from "./modal";

function updateCart(e) {
  const sectionNode = e.target.parentNode.parentNode;

  // get cart item original attributes (before update)
  const currentCartItem = CartItem(
    parseInt(sectionNode.getAttribute("data-productid"), 10),
    parseInt(sectionNode.getAttribute("data-quantity"), 10),
    sectionNode.getAttribute("data-cupsize"),
    sectionNode.getAttribute("data-milktype"),
  );

  // console.log("Old item", currentCartItem);

  // calculate new subtotal
  const newQuantity = parseInt(e.target.value, 10);
  const unitPrice = parseFloat(sectionNode.getAttribute("data-unitprice"));
  const newSubTotal = Math.round(newQuantity * unitPrice * 100) / 100;

  // update cart total
  let cartTotal = parseFloat(document.querySelector("#cart-total").textContent);
  cartTotal = cartTotal + unitPrice * (newQuantity - currentCartItem.quantity);
  document.querySelector("#cart-total").textContent = cartTotal
    .toFixed(2)
    .toString();

  // display new subtotal
  const priceNode = sectionNode.querySelector(".container > strong");
  priceNode.textContent = "Rs " + newSubTotal;

  // update quantity on actual node
  sectionNode.setAttribute("data-quantity", newQuantity);

  // update localstorage
  const currentCart = Cart();
  currentCart.removeItem(currentCartItem);

  // a quantity of 0 means to remove the item from cart
  if (newQuantity > 0) {
    currentCartItem.quantity = newQuantity;
    currentCart.addItem(currentCartItem);
  }
}

async function checkout() {
  const myCart = Cart();
  const items = myCart.getItems();

  const data = {
    items,
    store_id: document.querySelector("#store_location").value,
  };

  const response = await fetch(window.location.href + "/checkout", {
    method: "POST",
    body: JSON.stringify(data),
  });

  if (response.ok) {
    // Clear cart items from localStorage if checkout is successful
    myCart.clear();
    ModalManager("my-modal").openModal();
    return;
  }
  window.alert("Checkout failed");
}

/**
 * This function must be called after DOM has loaded.
 */
function initCartPage() {
  const quantityInputs = [
    ...document.querySelectorAll("section input[type='number']"),
  ];

  ModalManager("my-modal").init();

  document.querySelector("#checkout-btn").addEventListener("click", checkout);

  quantityInputs.forEach((input) => {
    input.addEventListener("change", updateCart);
  });
}

async function uploadCart() {
  const items = Cart().getItems();

  const response = await fetch(window.location.href + "/upload", {
    method: "POST",
    body: JSON.stringify(items),
  });

  // add loading delay of 1s
  await new Promise((r) => setTimeout(r, 1000));

  if (response.ok) {
    document.body.innerHTML = await response.text();
    initCartPage();
  }
}

window.addEventListener("DOMContentLoaded", uploadCart);
