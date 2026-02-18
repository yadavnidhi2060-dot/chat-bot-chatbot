let prompt = document.querySelector("#prompt");
let submitbtn = document.querySelector("#submit");
let chatContainer = document.querySelector(".chat-container");
let imagebtn = document.querySelector("#image");
let image = document.querySelector("#image img");
let imageinput = document.querySelector("#image input");

// ========== API SETUP ==========
//const Api_Url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyAYg0Qbp1jVwES4bpWVjRc4uIR95j2UV4E";


//Api_Url="https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyBQ4sS4JwUGKPD5-92UHtQoikpMryqF4Cw"



// To be used on Sunday
const Api_Url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyBKVVhcXYg5e-He-_Fu-M7tbln-5T-T-Wo";

//Api_Url="https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyCFeqkD1zIAmLUpzw4HbiElSkyYkN_5fX0"
//Api_Url="https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=
let user = {
    message: null,
    file: {
        mime_type: null,
        data: null
    }
};

// ========== MAIN RESPONSE FUNCTION ==========
// ========== MAIN RESPONSE FUNCTION ==========
async function generateResponse(aiChatBox) {
    let text = aiChatBox.querySelector(".ai-chat-area") || aiChatBox;

    let bodyContent = {
        contents: [
            {
                parts: [
                    { text: user.message },
                    ...(user.file.data ? [{ inline_data: user.file }] : [])
                ]
            }
        ]
    };

    try {
        const response = await fetch(Api_Url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(bodyContent)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        let aiMessage = data?.candidates?.[0]?.content?.parts?.[0]?.text;
        let apiResponse = aiMessage || "⚠ No response from API.";
        
        // Format the response properly
        apiResponse = formatResponse(apiResponse);

        text.innerHTML = apiResponse;

    } catch (error) {
        console.error("❌ API Request Failed:", error);
        console.error("📦 Request Payload:", JSON.stringify(bodyContent, null, 2));
        text.innerHTML = `⚠ Error generating response: ${error.message}`;
    } finally {
        chatContainer.scrollTo({ top: chatContainer.scrollHeight, behavior: "smooth" });
        image.src = "placeholder.svg";
        image.classList.remove("choose");
        user.file = { mime_type: null, data: null };
    }
}

// ========== FORMAT RESPONSE FUNCTION ==========
function formatResponse(text) {
    // Convert **bold** to <strong>
    text = text.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
    
    // Convert *italic* to <em>
    text = text.replace(/\*(.+?)\*/g, '<em>$1</em>');
    
    // Convert bullet points (* item) to HTML list items
    text = text.replace(/^\* (.+)$/gm, '<li>$1</li>');
    
    // Wrap consecutive <li> elements in <ul>
    text = text.replace(/(<li>.*<\/li>\s*)+/gs, '<ul>$&</ul>');
    
    // Convert numbered lists (1. item) to HTML ordered list
    text = text.replace(/^\d+\.\s+(.+)$/gm, '<li>$1</li>');
    text = text.replace(/(<li>.*<\/li>\s*)+/gs, function(match) {
        if (match.includes('<ul>')) return match; // Already wrapped
        return '<ol>' + match + '</ol>';
    });
    
    // Convert line breaks to <br> tags
    text = text.replace(/\n/g, '<br>');
    
    // Convert ### Headers to <h3>
    text = text.replace(/###\s+(.+?)<br>/g, '<h3>$1</h3>');
    
    // Convert ## Headers to <h2>
    text = text.replace(/##\s+(.+?)<br>/g, '<h2>$1</h2>');
    
    // Convert # Headers to <h1>
    text = text.replace(/#\s+(.+?)<br>/g, '<h1>$1</h1>');
    
    return text;
}

// ========== CREATE CHAT BOX ==========
function createChatBox(html, classes) {
    let div = document.createElement("div");
    div.innerHTML = html;
    div.classList.add(classes);
    return div;
}

// ========== HANDLE USER MESSAGE ==========
function handlechatResponse(userMessage) {
    if (!userMessage.trim()) return;

    user.message = userMessage;

    // User chat bubble
    let html = `
        <img src="user.png" alt="" width="8%">
        <div class="user-chat-area">
            ${user.message}
            ${user.file.data ? `<img src="data:${user.file.mime_type};base64,${user.file.data}" class="chooseimg" />` : ""}
        </div>
    `;

    prompt.value = "";
    let userChatBox = createChatBox(html, "user-chat-box");
    chatContainer.appendChild(userChatBox);
    chatContainer.scrollTo({ top: chatContainer.scrollHeight, behavior: "smooth" });

    // AI chat bubble
    setTimeout(() => {
        let html = `
            <img src="ai.png" alt="" width="10%">
            <div class="ai-chat-area">
                <img src="loading.webp" alt="Loading" class="load" width="50px">
            </div>`;
        let aiChatBox = createChatBox(html, "ai-chat-box");
        chatContainer.appendChild(aiChatBox);
        generateResponse(aiChatBox);
    }, 600);
}

// ========== EVENT LISTENERS ==========
prompt.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        handlechatResponse(prompt.value);
    }
});

submitbtn.addEventListener("click", () => {
    handlechatResponse(prompt.value);
});

imageinput.addEventListener("change", () => {
    const file = imageinput.files[0];
    if (!file) return;

    let reader = new FileReader();
    reader.onload = (e) => {
        let base64string = e.target.result.split(",")[1];
        user.file = {
            mime_type: file.type,
            data: base64string
        };
        image.src = `data:${user.file.mime_type};base64,${user.file.data}`;
        image.classList.add("choose");
    };
    reader.readAsDataURL(file);
});

imagebtn.addEventListener("click", () => {
    imagebtn.querySelector("input").click();
});

// ========== MEDILEX EXTRA FUNCTIONS ==========

async function getMedicalAdvice(question) {
    const prompt = `You are an experienced medical professional providing evidence-based health information.

QUERY: ${question}

Please provide a comprehensive medical response following this structure:

1. INITIAL ASSESSMENT
   - Brief overview of the condition/concern
   - Severity indicators (when to seek immediate care)

2. DETAILED MEDICAL INFORMATION
   - Possible causes or mechanisms
   - Common symptoms and progression
   - Risk factors to consider

3. EVIDENCE-BASED RECOMMENDATIONS
   - Self-care measures (if appropriate)
   - Lifestyle modifications
   - When to consult healthcare providers

4. IMPORTANT DISCLAIMERS
   - Remind that this is educational information, not a diagnosis
   - Emphasize the importance of professional medical consultation
   - Note any red flags requiring immediate attention`;

    const response = await fetch(Api_Url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            contents: [
                {
                    parts: [{ text: prompt }]
                }
            ]
        })
    });

    const data = await response.json();
    let result = data?.candidates?.[0]?.content?.parts?.[0]?.text || "⚠ No medical advice received.";
    return formatResponse(result); // Format before returning
}

async function getLegalAdvice(question) {
    const prompt = `You are a knowledgeable legal advisor providing general legal information and guidance.

LEGAL QUERY: ${question}

Please provide a comprehensive legal analysis following this structure:

1. LEGAL FRAMEWORK
   - Relevant areas of law (civil, criminal, contract, etc.)
   - Applicable jurisdiction considerations
   - Key legal principles involved

2. DETAILED ANALYSIS
   - Legal rights and obligations
   - Common legal interpretations
   - Potential outcomes or consequences
   - Relevant precedents or legal standards (general overview)

3. PRACTICAL GUIDANCE
   - Immediate steps to consider
   - Documentation or evidence to gather
   - Timeline considerations or deadlines
   - Potential legal remedies available

4. RISK ASSESSMENT
   - Possible legal implications
   - Best practices to protect rights
   - Common pitfalls to avoid

5. NEXT STEPS & DISCLAIMERS
   - When to consult a licensed attorney
   - Type of legal specialist needed (if specific)
   - Reminder that this is general information, not legal representation
   - Importance of jurisdiction-specific advice`;

    const response = await fetch(Api_Url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            contents: [
                {
                    parts: [{ text: prompt }]
                }
            ]
        })
    });

    const data = await response.json();
    let result = data?.candidates?.[0]?.content?.parts?.[0]?.text || "⚠ No legal advice received.";
    return formatResponse(result); // Format before returning
}

async function getMedilexResponse() {
    const userQuestion = document.getElementById('userQuestion').value.trim();
    if (!userQuestion) {
        alert("Please enter a question first.");
        return;
    }

    document.getElementById('medical').innerHTML = "⏳ Getting medical advice...";
    document.getElementById('legal').innerHTML = "⏳ Getting legal advice...";

    try {
        const [medical, legal] = await Promise.all([
            getMedicalAdvice(userQuestion),
            getLegalAdvice(userQuestion)
        ]);

        document.getElementById('medical').innerHTML = medical; // Use innerHTML instead of textContent
        document.getElementById('legal').innerHTML = legal; // Use innerHTML instead of textContent
    } catch (err) {
        console.error(err);
        document.getElementById('medical').innerHTML = "⚠ Failed to get medical advice.";
        document.getElementById('legal').innerHTML = "⚠ Failed to get legal advice.";
    }
}
