


//  cartCount = 0;

// document.querySelectorAll('.btn.btn-primary').forEach(button => {
//   button.addEventListener('click', () => {
//     cartCount++;
//     document.querySelector('.cart-count').textContent = cartCount;
//   });
// });



// offer page

// let cartCount = 0;

// document.querySelectorAll('.add-to-cart').forEach(button => {
//   button.addEventListener('click', () => {
//     cartCount++;
//     document.getElementById('cart-count').textContent = cartCount;
//   });
// });
// end
// 
// let cart = [];
// const cartCount = document.getElementById("cart-count");
// const cartItems = document.getElementById("cart-items");
// const cartDetails = document.getElementById("cart-details");
// const cartToggle = document.getElementById("cartToggle");

// // Add to Cart Buttons
// document.querySelectorAll(".add-to-cart").forEach(button => {
//   button.addEventListener("click", function () {
//     const productName = this.closest(".product-card").querySelector("h5").innerText;
//     cart.push(productName);
//     updateCart();
//   });
// });

// // Update Cart UI
// function updateCart() {
//   cartCount.innerText = cart.length;
//   cartItems.innerHTML = "";
//   cart.forEach((item, index) => {
//     const li = document.createElement("li");
//     li.classList.add("list-group-item");
//     li.textContent = `${index + 1}. ${item}`;
//     cartItems.appendChild(li);
//   });
// }

// // Toggle Cart Popup
// cartToggle.addEventListener("click", () => {
//   cartDetails.classList.toggle("visible");
//   cartDetails.classList.toggle("hidden");
// });

// // Clear Cart Function
// function clearCart() {
//   cart = [];
//   updateCart();
//   cartDetails.classList.add("hidden");
//   cartDetails.classList.remove("visible");
// }








// Cart variables
let cartItems = [];
const cartCount = document.getElementById("cart-count");
const cartList = document.getElementById("cart-list");

// Function to update cart icon and list
function updateCartDisplay() {
  cartCount.innerText = cartItems.length;

  // Clear previous items
  cartList.innerHTML = "";
  cartItems.forEach((item, index) => {
    const li = document.createElement("li");
    li.textContent = `${index + 1}. ${item}`;
    cartList.appendChild(li);
  });
}

// Add to cart from modal buttons
document.querySelectorAll(".modal .add-to-cart-btn").forEach(button => {
  button.addEventListener("click", function () {
    const itemName = this.getAttribute("data-item");
    cartItems.push(itemName);
    updateCartDisplay();
    
    // Optional: Close modal after adding
    const modal = bootstrap.Modal.getInstance(this.closest(".modal"));
    modal.hide();
  });
});



// compare
 const utensils = {
  fryingpans: [
    { name: "Stainless Steel Pan", material: "Stainless Steel", price: "₹500", weight: "Medium", heat: "Medium", durability: "High", stove: "All" },
    { name: "Copper Frying Pan", material: "Copper", price: "₹750", weight: "Light", heat: "High", durability: "Medium", stove: "Gas Only" }
  ],
  saucepans: [
    { name: "Non-Stick Sauce Pan", material: "Aluminium Non-Stick", price: "₹450", weight: "Light", heat: "High", durability: "Medium", stove: "All" },
    { name: "Stainless Sauce Pan", material: "Stainless Steel", price: "₹600", weight: "Medium", heat: "Medium", durability: "High", stove: "All" }
  ],
  pressurecookers: [
    { name: "Aluminium Cooker", material: "Aluminium", price: "₹900", weight: "Medium", heat: "High", durability: "Medium", stove: "Gas, Induction" },
    { name: "Steel Pressure Cooker", material: "Stainless Steel", price: "₹1100", weight: "Heavy", heat: "Medium", durability: "High", stove: "All" }
  ]
};

let selected = [];

function loadUtensils(category) {
  const grid = document.getElementById("productGrid");
  grid.innerHTML = "";
  selected = [];
  document.getElementById("comparisonTable").innerHTML = "";

  if (!utensils[category]) return;

  utensils[category].forEach((item, index) => {
    const card = `
      <div class="card">
        <div class="card-body">
          <h3>${item.name}</h3>
          <p>${item.material}</p>
          <input type="checkbox" onchange="toggleSelection('${category}', ${index})"> Compare
        </div>
      </div>
    `;
    grid.innerHTML += card;
  });
}

function toggleSelection(category, index) {
  const key = `${category}_${index}`;
  const exists = selected.findIndex(p => p.key === key);
  if (exists > -1) {
    selected.splice(exists, 1);
  } else {
    selected.push({ key, data: utensils[category][index] });
  }
}

function compareSelected() {
  if (selected.length < 2) {
    alert("Select at least 2 items to compare.");
    return;
  }

  const features = ["Material", "Price", "Weight", "Heat Conductivity", "Durability", "Stove Compatibility"];
  const keys = ["material", "price", "weight", "heat", "durability", "stove"];

  let table = `<table><thead><tr><th>Feature</th>`;
  selected.forEach(p => table += `<th>${p.data.name}</th>`);
  table += `</tr></thead><tbody>`;

  features.forEach((feature, i) => {
    table += `<tr><td><strong>${feature}</strong></td>`;
    selected.forEach(p => table += `<td>${p.data[keys[i]]}</td>`);
    table += `</tr>`;
  });

  table += "</tbody></table>";
  document.getElementById("comparisonTable").innerHTML = table;
}

const images = [
  { src: "images/p1.jpg", desc: "Stainless Steel Pan – Durable and all-purpose." },
  { src: "images/product32.jpg", desc: "Copper Pan – Great heat conductivity and lightweight." },
  { src: "images/product3.jpg", desc: "Non-stick Pan – Perfect for oil-free cooking." }
];

let current = 0;
function switchImage() {
  current = (current + 1) % images.length;
  document.getElementById("mainImage").src = images[current].src;
  document.getElementById("desc").textContent = images[current].desc;
}