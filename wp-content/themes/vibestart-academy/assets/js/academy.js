document.addEventListener("DOMContentLoaded", () => {
	const toggle = document.querySelector(".vibestart-menu-toggle");
	const navigation = document.querySelector(".vibestart-primary-navigation");

	if (toggle && navigation) {
		toggle.addEventListener("click", () => {
			const isOpen = toggle.getAttribute("aria-expanded") === "true";
			toggle.setAttribute("aria-expanded", String(!isOpen));
			navigation.classList.toggle("is-open", !isOpen);
		});
	}

	document.querySelectorAll("[data-placeholder-link='true']").forEach((link) => {
		link.addEventListener("click", (event) => event.preventDefault());
		link.addEventListener("keydown", (event) => {
			if (event.key === "Enter" || event.key === " ") {
				event.preventDefault();
			}
		});
	});
});
