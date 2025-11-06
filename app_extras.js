// Use same backend as your app; no change to existing files
const BASE_URL = "http://localhost/LinkedIn-Clone/backend/";

// --- Auth helpers reused ---
function logout(){ fetch(BASE_URL+"logout.php").then(()=>{ localStorage.clear(); location.href="index.html"; }); }
function userName(){ return localStorage.getItem("username") || ""; }
document.addEventListener("DOMContentLoaded", ()=>{
  const u = document.getElementById("username");
  if(u) u.textContent = "👋 " + userName();

  // Tabs
  document.querySelectorAll(".tab").forEach(btn=>{
    btn.onclick=()=>{
      document.querySelectorAll(".tab").forEach(b=>b.classList.remove("active"));
      document.querySelectorAll(".tab-pane").forEach(p=>p.classList.remove("active"));
      btn.classList.add("active");
      document.getElementById("tab-"+btn.dataset.tab).classList.add("active");
    };
  });

  // Theme toggle
  const t = document.getElementById("toggleTheme");
  if(t){ t.onclick=()=>document.body.classList.toggle("dark"); }

  // Page initializers
  if(location.pathname.endsWith("profile.html")){ loadPortfolio(); loadSkills(); loadFeedback(); loadVideo(); }
  if(location.pathname.endsWith("recruiter.html")){ loadDirectory(); }
  if(location.pathname.endsWith("jobs.html")){ loadJobs(); }
  if(location.pathname.endsWith("analytics.html")){ loadInsights(); }
  if(location.pathname.endsWith("quiz.html")){ buildQuiz(); }
});

// ---------- PORTFOLIO ----------
async function addPortfolio(){
  const title = val("p_title"), link = val("p_link"), description = val("p_desc"), tag = val("p_tag");
  let filename = "";
  const file = el("p_image").files[0];
  if(file){
    const fd = new FormData(); fd.append("image", file);
    const up = await fetch(BASE_URL+"portfolio_upload.php",{method:"POST", body:fd}); const j=await up.json();
    if(j.status==="success") filename=j.filename;
  }
  const res = await fetch(BASE_URL+"portfolio_add.php",{
    method:"POST", headers:{"Content-Type":"application/json"},
    body: JSON.stringify({title, link, description, image: filename, tag})
  }); const out = await res.json(); alert(out.message||out.status);
  loadPortfolio(); clear("p_title","p_link","p_desc","p_tag"); el("p_image").value="";
}
async function loadPortfolio(){
  const tag = val("filterTag");
  const res = await fetch(BASE_URL+"portfolio_list.php"+(tag?`?tag=${encodeURIComponent(tag)}`:""));
  const list = await res.json(); const box = el("portfolioList"); box.innerHTML="";
  list.forEach(p=>{
    box.innerHTML += `
      <div class="card">
        <h4>${escapeHTML(p.title)} ${p.verified_cert==1?"<span title='Verified'>✅</span>":""}</h4>
        ${p.image?`<img src="../uploads/${p.image}" alt="img">`:""}
        <p>${escapeHTML(p.description||"")}</p>
        <div><b>Tag:</b> ${escapeHTML(p.tag||"-")}</div>
        ${p.link?`<a href="${p.link}" target="_blank">🔗 Link</a>`:""}
      </div>`;
  });
}

// ---------- SKILLS ----------
async function addSkill(){
  const skill = val("skillName").trim(); if(!skill) return;
  const res = await fetch(BASE_URL+"skills_add.php",{method:"POST", headers:{"Content-Type":"application/json"}, body: JSON.stringify({skill})});
  const j = await res.json(); alert(j.message||j.status); loadSkills(); clear("skillName");
}
async function loadSkills(){
  const res = await fetch(BASE_URL+"skills_list.php"); const list = await res.json();
  const box = el("skillsList"); box.innerHTML="";
  list.forEach(s=>{
    box.innerHTML += `<div class="chip">${escapeHTML(s.skill_name)} <button onclick="endorse('${s.skill_name}')">🔥 ${s.endorsements}</button></div>`;
  });
}
async function endorse(skill){
  const res = await fetch(BASE_URL+"endorse.php",{method:"POST", headers:{"Content-Type":"application/json"}, body: JSON.stringify({skill})});
  await res.json(); loadSkills();
}

// ---------- RESUME (HTML → print to PDF) ----------
function generateResume(){ window.open(BASE_URL+"resume.php","_blank"); }

// ---------- VIDEO ----------
async function uploadVideo(){
  const f = el("videoFile").files[0]; if(!f) return alert("Choose a video");
  const fd = new FormData(); fd.append("video", f);
  const r = await fetch(BASE_URL+"video_upload.php",{method:"POST", body:fd}); const j=await r.json();
  alert(j.message||j.status); loadVideo();
}
async function loadVideo(){
  const r = await fetch(BASE_URL+"video_get.php"); const j = await r.json();
  const area = el("videoArea"); area.innerHTML="";
  if(j && j.filename){ area.innerHTML = `<video controls width="100%"><source src="../uploads/videos/${j.filename}"></video>`; }
}

// ---------- FEEDBACK ----------
async function sendFeedback(){
  const from_name = val("fbName")||"Recruiter"; const message=val("fbMessage"); if(!message) return;
  const r = await fetch(BASE_URL+"feedback_add.php",{method:"POST", headers:{"Content-Type":"application/json"}, body: JSON.stringify({from_name, message})});
  const j=await r.json(); alert(j.message||j.status); clear("fbName","fbMessage"); loadFeedback();
}
async function loadFeedback(){
  const r = await fetch(BASE_URL+"feedback_list.php"); const list = await r.json();
  const box = el("feedbackList"); box.innerHTML = list.map(f=>`<div class="card"><b>${escapeHTML(f.from_name)}</b><p>${escapeHTML(f.message)}</p><small>${new Date(f.created_at).toLocaleString()}</small></div>`).join("");
}

// ---------- RECRUITER DIRECTORY ----------
async function loadDirectory(){
  const s = val("searchSkill");
  const r = await fetch(BASE_URL+"recruiter_users.php"+(s?`?skill=${encodeURIComponent(s)}`:""));
  const list = await r.json(); const box = el("directory"); box.innerHTML="";
  list.forEach(u=>{
    box.innerHTML += `<div class="card">
      <h4>${escapeHTML(u.name)}</h4>
      <div>Email: ${escapeHTML(u.email||"-")}</div>
      <div>Skills: ${escapeHTML(u.skills||"-")}</div>
      <div>Posts: ${u.posts_count||0}</div>
      <a href="mailto:${u.email}"><button>Contact</button></a>
    </div>`;
  });
}

// ---------- JOBS ----------
async function postJob(){
  const body = {
    title: val("j_title"), company: val("j_company"),
    location: val("j_location"), skills: val("j_skills"), description: val("j_desc")
  };
  const r = await fetch(BASE_URL+"jobs_add.php",{method:"POST", headers:{"Content-Type":"application/json"}, body: JSON.stringify(body)});
  const j = await r.json(); alert(j.message||j.status); loadJobs();
}
async function loadJobs(){
  const f = val("jobFilter");
  const r = await fetch(BASE_URL+"jobs_list.php"+(f?`?skill=${encodeURIComponent(f)}`:""));
  const list = await r.json(); const box = el("jobsList"); box.innerHTML="";
  list.forEach(j=>{
    box.innerHTML += `<div class="card">
      <h4>${escapeHTML(j.title)} — ${escapeHTML(j.company||"")}</h4>
      <div>${escapeHTML(j.location||"")}</div>
      <p>${escapeHTML(j.description||"")}</p>
      <div><b>Skills:</b> ${escapeHTML(j.skills||"-")}</div>
      <button onclick="applyJob(${j.id})">Apply</button>
    </div>`;
  });
}
async function applyJob(id){
  const message = prompt("Short note to recruiter:");
  if(message===null) return;
  const r = await fetch(BASE_URL+"jobs_apply.php",{method:"POST", headers:{"Content-Type":"application/json"}, body: JSON.stringify({job_id:id, message})});
  const j = await r.json(); alert(j.message||j.status);
}

// ---------- ANALYTICS ----------
async function loadInsights(){
  const r = await fetch(BASE_URL+"analytics_summary.php"); const j = await r.json();
  el("insights").innerHTML = `
    <div class="card"><h4>Your totals</h4>
      <div>Posts: ${j.total_posts}</div>
      <div>Portfolio items: ${j.total_portfolio}</div>
      <div>Skills: ${j.total_skills}</div>
    </div>
    <div class="card"><h4>Last 7 days (posts per day)</h4>
      <pre>${j.posts_by_day.map(d=>`${d.day}: ${d.count}`).join('\n')}</pre>
    </div>`;
}

// ---------- QUIZ ----------
const QUIZ = [
  {q:"Which hook manages state?", a:["useEffect","useState","useMemo","useRef"], c:1},
  {q:"React elements are...", a:["Strings","Objects","Functions","Numbers"], c:1},
  {q:"Key prop is used to...", a:["Style","Identify list items","Bind events","SSR"], c:1},
  {q:"JSX compiles to...", a:["HTML","XML","React.createElement","CSS"], c:2},
  {q:"Memoization hook?", a:["useMemo","useLayoutEffect","useId","useSyncExternalStore"], c:0},
];
function buildQuiz(){
  el("quiz").innerHTML = QUIZ.map((it,i)=>(
    `<li>${it.q}<div>`+
    it.a.map((opt,idx)=>`<label><input type="radio" name="q${i}" value="${idx}"> ${opt}</label>`).join("<br>")+
    `</div></li>`
  )).join("");
}
async function submitQuiz(){
  let score=0;
  QUIZ.forEach((it,i)=>{ const v = document.querySelector(`input[name="q${i}"]:checked`); if(v && +v.value===it.c) score++; });
  el("quizResult").textContent = `Score: ${score}/${QUIZ.length}`;
  await fetch(BASE_URL+"quiz_submit.php",{method:"POST", headers:{"Content-Type":"application/json"}, body: JSON.stringify({skill:"React", score})});
}

// ---------- helpers ----------
function el(id){ return document.getElementById(id); }
function val(id){ const e=el(id); return e?e.value:""; }
function clear(...ids){ ids.forEach(i=>{ const e=el(i); if(e) e.value=""; }); }
function escapeHTML(s){ return (s||"").replace(/[&<>"']/g, m=>({ "&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;" }[m])); }
