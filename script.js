const BASE_URL = "http://localhost/LinkedIn-Clone/backend/";


function login() {
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  fetch(BASE_URL + "login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ email, password })
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        localStorage.setItem("username", data.user);
        window.location.href = "feed.html";
      } else {
        alert(data.message);
      }
    });
}

function toggleSignup() {
  document.getElementById("auth-container").innerHTML = `
    <h2>Sign Up</h2>
    <input type="text" id="name" placeholder="Full Name">
    <input type="email" id="email" placeholder="Email">
    <input type="password" id="password" placeholder="Password">
    <button onclick="signup()">Sign Up</button>
    <p>Already have an account? <a href="#" onclick="location.reload()">Login</a></p>`;
}

function signup() {
  const name = document.getElementById("name").value;
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  fetch(BASE_URL + "signup.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ name, email, password })
  })
    .then(res => res.json())
    .then(data => alert(data.message));
}

async function createPost() {
  const content = document.getElementById("postContent").value;
  const imageFile = document.getElementById("imageInput").files[0];
  let filename = "";

  if(imageFile){
    const formData = new FormData();
    formData.append("image", imageFile);
    const res = await fetch(BASE_URL + "upload.php", {method:"POST", body:formData});
    const data = await res.json();
    if(data.status==="success") filename = data.filename;
  }

  await fetch(BASE_URL + "post.php", {
    method: "POST",
    headers: {"Content-Type": "application/json"},
    body: JSON.stringify({ content, image: filename })
  });
  document.getElementById("postContent").value = "";
  document.getElementById("imageInput").value = "";
  loadPosts();
}


function loadPosts() {
  fetch(BASE_URL + "fetch_posts.php")
    .then(res => res.json())
    .then(posts => {
      const feed = document.getElementById("feed");
      feed.innerHTML = "";
      posts.forEach(post => {
        const reactions = JSON.parse(post.reactions || '{"like":0,"love":0,"clap":0,"fire":0}');
        const imageHTML = post.image ? `<img src="../uploads/${post.image}" style="max-width:100%;border-radius:8px;">` : "";
        feed.innerHTML += `
          <div class="post">
            <b>${post.name}</b>
            <p>${post.content}</p>
            ${imageHTML}
            <div>
              <button onclick="react(${post.id},'like')">👍 ${reactions.like}</button>
              <button onclick="react(${post.id},'love')">❤️ ${reactions.love}</button>
              <button onclick="react(${post.id},'clap')">👏 ${reactions.clap}</button>
              <button onclick="react(${post.id},'fire')">🔥 ${reactions.fire}</button>
            </div>
            <input placeholder="Add positive comment..." onkeydown="if(event.key==='Enter') addComment(${post.id}, this.value);">
            <button onclick="editPost(${post.id})">✏️ Edit</button>
            <button onclick="deletePost(${post.id})">🗑️ Delete</button>
            <small>${new Date(post.created_at).toLocaleString()}</small>
          </div>`;
      });
    });
}

function react(postId, emoji){
  fetch(BASE_URL + "react.php", {
    method: "POST",
    headers: {"Content-Type":"application/json"},
    body: JSON.stringify({post_id: postId, reaction: emoji})
  }).then(()=>loadPosts());
}

function addComment(postId, text){
  fetch(BASE_URL + "comment.php", {
    method: "POST",
    headers: {"Content-Type":"application/json"},
    body: JSON.stringify({post_id: postId, comment: text})
  }).then(()=>loadPosts());
}

function editPost(postId){
  const newContent = prompt("Edit your post:");
  if(!newContent) return;
  fetch(BASE_URL + "edit_post.php", {
    method: "POST",
    headers: {"Content-Type":"application/json"},
    body: JSON.stringify({post_id: postId, content: newContent})
  }).then(()=>loadPosts());
}


function logout() {
  fetch(BASE_URL + "logout.php").then(() => {
    localStorage.clear();
    window.location.href = "index.html";
  });
}

if (window.location.pathname.endsWith("feed.html")) {
  document.getElementById("username").innerText = "👋 " + localStorage.getItem("username");
  loadPosts();
}