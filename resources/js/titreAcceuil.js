const word1 = "K R E Y A T I K";
const word2 = "S T U D I O";

const container1 = document.getElementById("word-kreyatik");
const container2 = document.getElementById("word-studio");

function animateWord(text, container, delayOffset = 0) {
    [...text].forEach((char, i) => {
        const span = document.createElement("span");
        span.textContent = char;
        span.className = "letter";
        container.appendChild(span);

        setTimeout(() => {
            span.style.opacity = "1";
            span.style.transform = "scale(1) translateY(0)";
        }, (i + delayOffset) * 100);
    });
}

animateWord(word1, container1);
animateWord(word2, container2, word1.replace(/ /g, "").length);