//select elements
const openSidebarIcon = document.querySelector(".menu-icon.open");
const closeSidebarIcon = document.querySelector(".menu-icon.close");
const sidebar = document.querySelector(".sidebar");

//open sidebar when the page loads if it is not already open
openSidebarIcon.addEventListener("click", () => {
  sidebar.classList.add("openned");
});

closeSidebarIcon.addEventListener("click", () => {
  sidebar.classList.remove("openned");
});
const fileInput = document.getElementById("fileInput");
const filePreview = document.getElementById("filePreview");

// Select all checkboxes functionality
document.getElementById("selectAll").addEventListener("change", function () {
  const checkboxes = document.querySelectorAll(".row-checkbox");
  checkboxes.forEach((cb) => (cb.checked = this.checked));
});

// show the file preview when the user uploads a file and hide it when the user removes the file
fileInput.addEventListener("change", function () {
  filePreview.innerHTML = ""; // Clear existing previews

  const imageFiles = Array.from(this.files).filter((file) =>
    file.type.startsWith("image/")
  );
  if (imageFiles.length === 0) {
    filePreview.classList.add("d-none");
    return;
  }
  

  filePreview.classList.remove("d-none");
  imageFiles.forEach((file) => {
    const reader = new FileReader();
    reader.onload = function (e) {
      const imgWrapper = document.createElement("div");
      imgWrapper.classList.add("preview-item");

      imgWrapper.innerHTML = `
        <img src="${e.target.result}" class="img-thumbnail" style="max-width: 120px; max-height: 120px;" />
        <div class="small text-center mt-1 text-muted">${file.name}</div>
      `;

      filePreview.appendChild(imgWrapper);
    };
    reader.readAsDataURL(file);
  });
});
