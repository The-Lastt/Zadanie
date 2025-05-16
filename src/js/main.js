{
	function checkScrollPosition()
	{
		if (window.scrollY > 200)
			document.body.classList.add("scrollNotOnTop");
		else
			document.body.classList.remove("scrollNotOnTop");
	}

	window.addEventListener("scroll", () => checkScrollPosition());
	checkScrollPosition();
}