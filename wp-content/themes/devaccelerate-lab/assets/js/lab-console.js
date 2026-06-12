document.addEventListener("DOMContentLoaded", () => {
	const controller = document.querySelector(".devaccelerate-nav-toggle");
	const consoleNavigation = document.querySelector(".devaccelerate-console-nav");

	if (controller && consoleNavigation) {
		controller.addEventListener("click", () => {
			const nextState = controller.getAttribute("aria-expanded") !== "true";
			controller.setAttribute("aria-expanded", String(nextState));
			consoleNavigation.classList.toggle("is-open", nextState);
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
