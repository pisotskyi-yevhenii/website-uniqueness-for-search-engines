document.addEventListener("DOMContentLoaded", () => {
	const controller = document.querySelector(".devaccelerate-nav-toggle");
	const consoleNavigation = document.querySelector(".devaccelerate-console-nav");
	const closeController = document.querySelector(".devaccelerate-console-nav__close");

	if (controller && consoleNavigation) {
		const symbol = controller.querySelector(".devaccelerate-nav-toggle__symbol");
		const label = controller.querySelector(".devaccelerate-nav-toggle__label");
		const openLabel = controller.dataset.openLabel;
		const closeLabel = controller.dataset.closeLabel;

		const setNavigationState = (isOpen) => {
			controller.setAttribute("aria-expanded", String(isOpen));
			consoleNavigation.classList.toggle("is-open", isOpen);

			if (symbol) {
				symbol.textContent = isOpen ? "[-]" : "[+]";
			}

			if (label) {
				label.textContent = isOpen ? closeLabel : openLabel;
			}
		};

		controller.addEventListener("click", () => {
			setNavigationState(controller.getAttribute("aria-expanded") !== "true");
		});

		closeController?.addEventListener("click", () => setNavigationState(false));

		document.addEventListener("click", (event) => {
			if (
				controller.getAttribute("aria-expanded") === "true"
				&& !controller.contains(event.target)
				&& !consoleNavigation.contains(event.target)
			) {
				setNavigationState(false);
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
