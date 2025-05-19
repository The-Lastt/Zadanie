{
	const header = document.querySelector("header .header-contents");
	let headerOffset = header.offsetTop;
	let lastScrollTop = window.scrollY;
	function checkScrollPosition()
	{
		if (window.scrollY > 200)
			document.body.classList.add("scrollNotOnTop");
		else
			document.body.classList.remove("scrollNotOnTop");

		if (window.scrollY > headerOffset)
			document.body.classList.add("stickyHeader");
		else
			document.body.classList.remove("stickyHeader");

	
			if (window.scrollY > lastScrollTop)
			{
				document.body.classList.add("scrollDown");
				document.body.classList.remove("scrollUp");
			}
			else if (window.scrollY < lastScrollTop)
			{
				document.body.classList.remove("scrollDown");
				document.body.classList.add("scrollUp");
			}


		lastScrollTop = window.scrollY <= 0 ? 0 : window.scrollY;
	}

	window.addEventListener("scroll", () => checkScrollPosition());
	checkScrollPosition();

	document.querySelector("header .mobile-menu-toggle").addEventListener("click", () =>
		{
			document.body.classList.toggle("mobileMenuOpen");
		});
		
		for (const li of document.querySelectorAll("header .mobile-menu li"))
		{
			const ul = li.querySelector("ul");
			if (ul)
			{
				li.querySelector("button").addEventListener("click", (e) =>
				{
					e.preventDefault();
					li.classList.toggle("opened");
					ul.style.maxHeight = li.classList.contains("opened") ? `${ul.scrollHeight}px` : "0";
				});
		
				ul.addEventListener("click", (e) =>
				{
					e.stopPropagation();
				});
		
				// Initialize maxHeight to 0 for closed menus
				ul.style.transition = "max-height 0.5s ease";
				ul.style.overflow = "hidden";
				ul.style.maxHeight = "0";
			}
		}

	document.querySelector('.generated-form').addEventListener('submit', async function(e) {
		e.preventDefault();
		const form = e.target;
		const formData = new FormData(form);
		const data = Object.fromEntries(formData.entries());

		const responseBox = document.getElementById('form-response');
		responseBox.innerHTML = '';

		if (data.name.length < 3 || data.name.length > 50) {
			responseBox.innerHTML += `<p style="color:red">Imię musi zawierać od 3 do 50 znaków.</p>`;
			return;
		}
		if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) {
			responseBox.innerHTML += `<p style="color:red">Podaj poprawny adres e-mail.</p>`;
			return;
		}

		const res = await fetch(form.action, {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify(data)
		});

		const result = await res.json();
		if (res.ok) {
			responseBox.innerHTML = `<p style="color:green">${result.message}</p>`;
			form.reset();
		} else {
			const errors = result.errors;
			for (const field in errors) {
				responseBox.innerHTML += `<p style="color:red">${errors[field]}</p>`;
			}
		}
	});

}