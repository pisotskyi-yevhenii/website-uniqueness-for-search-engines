document.addEventListener("DOMContentLoaded", () => {
	const controller = document.querySelector(".devaccelerate-nav-toggle");
	const consoleNavigation = document.querySelector(".devaccelerate-console-nav");

	if (controller && consoleNavigation) {
		const symbol = controller.querySelector(".devaccelerate-nav-toggle__symbol");
		const label = controller.querySelector(".devaccelerate-nav-toggle__label");
		const openLabel = controller.dataset.openLabel;
		const closeLabel = controller.dataset.closeLabel;

		controller.addEventListener("click", () => {
			const nextState = controller.getAttribute("aria-expanded") !== "true";
			controller.setAttribute("aria-expanded", String(nextState));
			consoleNavigation.classList.toggle("is-open", nextState);

			if (symbol) {
				symbol.textContent = nextState ? "[-]" : "[+]";
			}

			if (label) {
				label.textContent = nextState ? closeLabel : openLabel;
			}
		});
	}

	const placeholders = document.querySelectorAll("[data-console-placeholder='true']");
	placeholders.forEach((element) => {
		element.addEventListener("click", (event) => event.preventDefault());
		element.addEventListener("keydown", (event) => {
			if (["Enter", " "].includes(event.key)) {
				event.preventDefault();
			}
		});
	});
});
