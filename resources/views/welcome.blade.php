<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Eloquent ORM — Interactive Cheatsheet & Demo</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { padding-top: 70px; }
    pre { background:#0f1720; color:#e6eef6; padding:12px; border-radius:6px; overflow:auto; }
    .sql { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, "Roboto Mono", "Courier New", monospace; font-size: .9rem; color:#0b6623; }
    .result-table td, .result-table th { vertical-align: middle; }
    .badge-rel { font-size: .75rem; }
  </style>
</head>
<body>

  <!-- NAV -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">Eloquent Interactive Demo</a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#cheatsheet">Cheatsheet</a></li>
          <li class="nav-item"><a class="nav-link" href="#examples">Live Examples</a></li>
          <li class="nav-item"><a class="nav-link" href="#sections">Relationship Pages</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <header class="py-5 bg-light text-center mb-4">
    <div class="container">
      <h1 class="display-6 fw-bold">Laravel Eloquent — Interactive Cheatsheet & Live Demos</h1>
      <p class="lead text-muted">Explore relationship types, see sample Eloquent code, view generated SQL, and run examples on fake data — all client-side.</p>
    </div>
  </header>

  <div class="container">

    <!-- Cheatsheet -->
    <section id="cheatsheet" class="mb-5">
      <h2>Cheatsheet</h2>
      <p class="text-muted">Quick reference of Eloquent relationships and the typical model method definitions.</p>

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-dark">
            <tr>
              <th>Relationship</th>
              <th>Purpose</th>
              <th>Model Method (Example)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>One-to-One</strong></td>
              <td>One model has one related model (User -> Profile)</td>
              <td><code>public function profile() { return $this->hasOne(Profile::class); }</code></td>
            </tr>

            <tr>
              <td><strong>One-to-Many</strong></td>
              <td>One model has many related models (Post -> Comments)</td>
              <td><code>public function comments() { return $this->hasMany(Comment::class); }</code></td>
            </tr>

            <tr>
              <td><strong>Many-to-Many</strong></td>
              <td>Many models belong to many other models (User <-> Role)</td>
              <td><code>public function roles() { return $this->belongsToMany(Role::class); }</code></td>
            </tr>

            <tr>
              <td><strong>Has One / Many Through</strong></td>
              <td>Access distant relations through an intermediate model (Country -> Post through User)</td>
              <td><code>public function posts() { return $this->hasManyThrough(Post::class, User::class); }</code></td>
            </tr>

            <tr>
              <td><strong>Polymorphic (morphOne / morphMany)</strong></td>
              <td>A model can belong to more than one other model on a single association (Comment -> Post or Video)</td>
              <td><code>public function comments() { return $this->morphMany(Comment::class, 'commentable'); }</code></td>
            </tr>

            <tr>
              <td><strong>Many-to-Many Polymorphic</strong></td>
              <td>Tags for posts, videos, etc. (single tags table used by multiple models)</td>
              <td><code>public function tags() { return $this->morphToMany(Tag::class, 'taggable'); }</code></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Live examples -->
    <section id="examples" class="mb-5">
      <h2>Live Examples (with fake data)</h2>
      <p class="text-muted">Click <em>Show SQL</em> to see a simulated query that Eloquent would generate. Click <em>Run Example</em> to execute the example on the fake data and see results.</p>

      <!-- Tabs -->
      <ul class="nav nav-pills mb-3" id="exampleTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="one2one-tab" data-bs-toggle="pill" data-bs-target="#one2one" type="button">One-to-One</button>
        </li>
        <li class="nav-item"><button class="nav-link" id="one2many-tab" data-bs-toggle="pill" data-bs-target="#one2many" type="button">One-to-Many</button></li>
        <li class="nav-item"><button class="nav-link" id="many2many-tab" data-bs-toggle="pill" data-bs-target="#many2many" type="button">Many-to-Many</button></li>
        <li class="nav-item"><button class="nav-link" id="hasthrough-tab" data-bs-toggle="pill" data-bs-target="#hasthrough" type="button">HasManyThrough</button></li>
        <li class="nav-item"><button class="nav-link" id="poly-tab" data-bs-toggle="pill" data-bs-target="#poly" type="button">Polymorphic</button></li>
        <li class="nav-item"><button class="nav-link" id="manypoly-tab" data-bs-toggle="pill" data-bs-target="#manypoly" type="button">Many-to-Many Polymorphic</button></li>
      </ul>

      <div class="tab-content">
        <!-- One-to-One -->
        <div class="tab-pane fade show active" id="one2one">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">One-to-One — User & Profile</h5>
              <p class="text-muted">Model snippet: <code>User->hasOne(Profile)</code></p>

              <div class="mb-3">
                <button class="btn btn-outline-primary me-2" onclick="showSQL('one2one')">Show SQL</button>
                <button class="btn btn-primary" onclick="runExample('one2one')">Run Example</button>
                <span class="ms-3 badge bg-info badge-rel">Fake DB rows: <strong id="one2one-count">0</strong></span>
              </div>

              <div id="one2one-sql" class="mb-2" style="display:none;">
                <label class="form-label">Simulated SQL:</label>
                <pre class="sql" id="one2one-sql-pre"></pre>
              </div>

              <div id="one2one-result" style="display:none;">
                <label class="form-label">Result (JS object):</label>
                <pre id="one2one-json"></pre>
              </div>

            </div>
          </div>
        </div>

        <!-- One-to-Many -->
        <div class="tab-pane fade" id="one2many">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">One-to-Many — Post & Comments</h5>
              <p class="text-muted">Model snippet: <code>Post->hasMany(Comment)</code></p>

              <div class="mb-3">
                <button class="btn btn-outline-primary me-2" onclick="showSQL('one2many')">Show SQL</button>
                <button class="btn btn-primary" onclick="runExample('one2many')">Run Example</button>
                <span class="ms-3 badge bg-info badge-rel">Fake DB rows: <strong id="one2many-count">0</strong></span>
              </div>

              <div id="one2many-sql" class="mb-2" style="display:none;">
                <label class="form-label">Simulated SQL:</label>
                <pre class="sql" id="one2many-sql-pre"></pre>
              </div>

              <div id="one2many-result" style="display:none;">
                <label class="form-label">Result (table):</label>
                <div class="table-responsive">
                  <table class="table table-sm table-striped result-table" id="one2many-table"></table>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Many-to-Many -->
        <div class="tab-pane fade" id="many2many">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Many-to-Many — User & Role (pivot: role_user)</h5>
              <p class="text-muted">Model snippet: <code>User->belongsToMany(Role)</code></p>

              <div class="mb-3">
                <button class="btn btn-outline-primary me-2" onclick="showSQL('many2many')">Show SQL</button>
                <button class="btn btn-primary" onclick="runExample('many2many')">Run Example</button>
                <span class="ms-3 badge bg-info badge-rel">Fake DB rows: <strong id="many2many-count">0</strong></span>
              </div>

              <div id="many2many-sql" class="mb-2" style="display:none;">
                <label class="form-label">Simulated SQL:</label>
                <pre class="sql" id="many2many-sql-pre"></pre>
              </div>

              <div id="many2many-result" style="display:none;">
                <label class="form-label">Result (table):</label>
                <div class="table-responsive">
                  <table class="table table-sm table-striped result-table" id="many2many-table"></table>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- HasManyThrough -->
        <div class="tab-pane fade" id="hasthrough">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">HasManyThrough — Country -> User -> Post</h5>
              <p class="text-muted">Model snippet: <code>Country->hasManyThrough(Post, User)</code></p>

              <div class="mb-3">
                <button class="btn btn-outline-primary me-2" onclick="showSQL('hasthrough')">Show SQL</button>
                <button class="btn btn-primary" onclick="runExample('hasthrough')">Run Example</button>
                <span class="ms-3 badge bg-info badge-rel">Fake DB rows: <strong id="hasthrough-count">0</strong></span>
              </div>

              <div id="hasthrough-sql" class="mb-2" style="display:none;">
                <label class="form-label">Simulated SQL:</label>
                <pre class="sql" id="hasthrough-sql-pre"></pre>
              </div>

              <div id="hasthrough-result" style="display:none;">
                <label class="form-label">Result (table):</label>
                <div class="table-responsive">
                  <table class="table table-sm table-striped result-table" id="hasthrough-table"></table>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Polymorphic -->
        <div class="tab-pane fade" id="poly">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Polymorphic — Comment -> (Post | Video)</h5>
              <p class="text-muted">Model snippet: <code>Comment->morphTo()</code> and <code>Post->morphMany(Comment, 'commentable')</code></p>

              <div class="mb-3">
                <button class="btn btn-outline-primary me-2" onclick="showSQL('poly')">Show SQL</button>
                <button class="btn btn-primary" onclick="runExample('poly')">Run Example</button>
                <span class="ms-3 badge bg-info badge-rel">Fake DB rows: <strong id="poly-count">0</strong></span>
              </div>

              <div id="poly-sql" class="mb-2" style="display:none;">
                <label class="form-label">Simulated SQL:</label>
                <pre class="sql" id="poly-sql-pre"></pre>
              </div>

              <div id="poly-result" style="display:none;">
                <label class="form-label">Result (table):</label>
                <div class="table-responsive">
                  <table class="table table-sm table-striped result-table" id="poly-table"></table>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Many-to-Many Polymorphic -->
        <div class="tab-pane fade" id="manypoly">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Many-to-Many Polymorphic — Tags</h5>
              <p class="text-muted">Model snippet: <code>Post->morphToMany(Tag, 'taggable')</code></p>

              <div class="mb-3">
                <button class="btn btn-outline-primary me-2" onclick="showSQL('manypoly')">Show SQL</button>
                <button class="btn btn-primary" onclick="runExample('manypoly')">Run Example</button>
                <span class="ms-3 badge bg-info badge-rel">Fake DB rows: <strong id="manypoly-count">0</strong></span>
              </div>

              <div id="manypoly-sql" class="mb-2" style="display:none;">
                <label class="form-label">Simulated SQL:</label>
                <pre class="sql" id="manypoly-sql-pre"></pre>
              </div>

              <div id="manypoly-result" style="display:none;">
                <label class="form-label">Result (table):</label>
                <div class="table-responsive">
                  <table class="table table-sm table-striped result-table" id="manypoly-table"></table>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- Relationship Pages list -->
    <section id="sections" class="mb-5">
      <h2>Separate Relationship Pages</h2>
      <p class="text-muted">Links to quickly jump to the dedicated sections above (simulating separate pages).</p>
      <div class="list-group">
        <a class="list-group-item list-group-item-action" href="#one2one">One-to-One</a>
        <a class="list-group-item list-group-item-action" href="#one2many">One-to-Many</a>
        <a class="list-group-item list-group-item-action" href="#many2many">Many-to-Many</a>
        <a class="list-group-item list-group-item-action" href="#hasthrough">HasManyThrough</a>
        <a class="list-group-item list-group-item-action" href="#poly">Polymorphic</a>
        <a class="list-group-item list-group-item-action" href="#manypoly">Many-to-Many Polymorphic</a>
      </div>
    </section>

  </div>

  <!-- FOOTER -->
  <footer class="bg-dark text-white py-3 text-center mt-4">
    <div class="container">
      <small>© 2025 Eloquent Interactive Demo — For learning & teaching</small>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Demo JS: fake data + simulation logic -->
  <script>
    /* ---------------------------
       Fake data (in-memory JS objects)
       --------------------------- */
    const db = {
      users: [
        { id: 1, name: 'Alice', country_id: 1 },
        { id: 2, name: 'Bob', country_id: 1 },
        { id: 3, name: 'Carol', country_id: 2 }
      ],
      profiles: [
        { id: 1, user_id: 1, bio: 'Frontend dev' },
        { id: 2, user_id: 2, bio: 'Backend dev' }
      ],
      posts: [
        { id: 1, user_id: 1, title: 'Hello World' },
        { id: 2, user_id: 2, title: 'Eloquent Tips' },
        { id: 3, user_id: 3, title: 'Country Post' }
      ],
      comments: [
        { id: 1, post_id: 1, body: 'Nice post!' },
        { id: 2, post_id: 1, body: 'Thanks for sharing' },
        { id: 3, post_id: 2, body: 'Good tips' }
      ],
      roles: [
        { id: 1, name: 'admin' },
        { id: 2, name: 'editor' }
      ],
      role_user: [ // pivot table
        { user_id: 1, role_id: 1 },
        { user_id: 2, role_id: 2 },
        { user_id: 1, role_id: 2 }
      ],
      countries: [
        { id: 1, name: 'Bangland' },
        { id: 2, name: 'Examplestan' }
      ],
      videos: [
        { id: 1, title: 'Intro Video' }
      ],
      polymorphic_comments: [ // comments for posts and videos (commentable_id, commentable_type)
        { id: 1, commentable_id: 1, commentable_type: 'Post', body: 'Great post' },
        { id: 2, commentable_id: 1, commentable_type: 'Video', body: 'Nice video' }
      ],
      tags: [
        { id: 1, name: 'php' },
        { id: 2, name: 'laravel' }
      ],
      taggables: [ // polymorphic pivot table
        { tag_id: 1, taggable_id: 1, taggable_type: 'Post' },
        { tag_id: 2, taggable_id: 1, taggable_type: 'Post' },
        { tag_id: 2, taggable_id: 1, taggable_type: 'Video' }
      ]
    };

    /* show counts */
    document.getElementById('one2one-count').innerText = db.users.length + ' users / ' + db.profiles.length + ' profiles';
    document.getElementById('one2many-count').innerText = db.posts.length + ' posts / ' + db.comments.length + ' comments';
    document.getElementById('many2many-count').innerText = db.users.length + ' users / ' + db.roles.length + ' roles / pivot ' + db.role_user.length;
    document.getElementById('hasthrough-count').innerText = db.countries.length + ' countries / ' + db.posts.length + ' posts';
    document.getElementById('poly-count').innerText = db.polymorphic_comments.length + ' comments';
    document.getElementById('manypoly-count').innerText = db.tags.length + ' tags / ' + db.taggables.length + ' taggables';

    /* ---------------------------
       SQL simulation & runner
       --------------------------- */
    function showSQL(example) {
      switch(example) {
        case 'one2one':
          document.getElementById('one2one-sql-pre').innerText =
`// Eloquent
$user = User::with('profile')->find(1);

// Approximate SQL:
select * from "users" where "id" = 1 limit 1;
select * from "profiles" where "user_id" = 1;`;
          document.getElementById('one2one-sql').style.display = 'block';
          break;

        case 'one2many':
          document.getElementById('one2many-sql-pre').innerText =
`// Eloquent
$post = Post::with('comments')->find(1);

// Approximate SQL:
select * from "posts" where "id" = 1 limit 1;
select * from "comments" where "post_id" = 1;`;
          document.getElementById('one2many-sql').style.display = 'block';
          break;

        case 'many2many':
          document.getElementById('many2many-sql-pre').innerText =
`// Eloquent
$user = User::with('roles')->find(1);

// Approximate SQL:
select * from "users" where "id" = 1 limit 1;
select roles.* from "roles"
inner join "role_user" on "roles"."id" = "role_user"."role_id"
where "role_user"."user_id" = 1;`;
          document.getElementById('many2many-sql').style.display = 'block';
          break;

        case 'hasthrough':
          document.getElementById('hasthrough-sql-pre').innerText =
`// Eloquent
$country = Country::find(1);
$posts = $country->posts; // hasManyThrough(Post, User)

// Approximate SQL:
select * from "countries" where "id" = 1 limit 1;
select posts.* from "posts"
inner join "users" on "users"."id" = "posts"."user_id"
where "users"."country_id" = 1;`;
          document.getElementById('hasthrough-sql').style.display = 'block';
          break;

        case 'poly':
          document.getElementById('poly-sql-pre').innerText =
`// Eloquent
$post = Post::with('comments')->find(1);

// Approximate SQL:
select * from "posts" where "id" = 1 limit 1;
select * from "polymorphic_comments"
where commentable_id = 1 and commentable_type = 'Post';`;
          document.getElementById('poly-sql').style.display = 'block';
          break;

        case 'manypoly':
          document.getElementById('manypoly-sql-pre').innerText =
`// Eloquent
$post = Post::with('tags')->find(1);

// Approximate SQL:
select * from "posts" where "id" = 1 limit 1;
select tags.* from "tags"
inner join "taggables" on "tags"."id" = "taggables"."tag_id"
where taggables.taggable_id = 1 and taggables.taggable_type = 'Post';`;
          document.getElementById('manypoly-sql').style.display = 'block';
          break;
      }
    }

    function runExample(example) {
      // hide all result blocks first
      const blocks = ['one2one', 'one2many', 'many2many', 'hasthrough', 'poly', 'manypoly'];
      blocks.forEach(b => {
        const res = document.getElementById(b + '-result');
        if (res) res.style.display = 'none';
      });

      switch(example) {
        case 'one2one':
          // Simulate: $user = User::with('profile')->find(1)
          const user = db.users.find(u => u.id === 1);
          const profile = db.profiles.find(p => p.user_id === user.id) || null;
          document.getElementById('one2one-json').innerText = JSON.stringify({ user, profile }, null, 2);
          document.getElementById('one2one-result').style.display = 'block';
          break;

        case 'one2many':
          // Simulate: $post = Post::with('comments')->find(1)
          const post = db.posts.find(p => p.id === 1);
          const comments = db.comments.filter(c => c.post_id === post.id);
          // build table
          const table = document.getElementById('one2many-table');
          table.innerHTML = `
            <thead><tr><th>Post</th><th>Comment ID</th><th>Body</th></tr></thead>
            <tbody>
              ${comments.map(c => `<tr><td>${escapeHTML(post.title)}</td><td>${c.id}</td><td>${escapeHTML(c.body)}</td></tr>`).join('')}
            </tbody>
          `;
          document.getElementById('one2many-result').style.display = 'block';
          break;

        case 'many2many':
          // Simulate: $user = User::with('roles')->find(1)
          const mmUser = db.users.find(u => u.id === 1);
          // find pivot entries
          const pivot = db.role_user.filter(r => r.user_id === mmUser.id);
          const mmRoles = pivot.map(p => db.roles.find(r => r.id === p.role_id));
          const mmTable = document.getElementById('many2many-table');
          mmTable.innerHTML = `
            <thead><tr><th>User</th><th>Role</th></tr></thead>
            <tbody>
              ${mmRoles.map(r => `<tr><td>${escapeHTML(mmUser.name)}</td><td>${escapeHTML(r.name)}</td></tr>`).join('')}
            </tbody>
          `;
          document.getElementById('many2many-result').style.display = 'block';
          break;

        case 'hasthrough':
          // Simulate: Country(id=1) -> posts via users
          const country = db.countries.find(c => c.id === 1);
          const countryUsers = db.users.filter(u => u.country_id === country.id);
          const countryUserIds = countryUsers.map(u => u.id);
          const countryPosts = db.posts.filter(p => countryUserIds.includes(p.user_id));
          const htTable = document.getElementById('hasthrough-table');
          htTable.innerHTML = `
            <thead><tr><th>Country</th><th>Post ID</th><th>Title</th><th>Author</th></tr></thead>
            <tbody>
              ${countryPosts.map(p => {
                const author = db.users.find(u => u.id === p.user_id);
                return `<tr><td>${escapeHTML(country.name)}</td><td>${p.id}</td><td>${escapeHTML(p.title)}</td><td>${escapeHTML(author.name)}</td></tr>`;
              }).join('')}
            </tbody>
          `;
          document.getElementById('hasthrough-result').style.display = 'block';
          break;

        case 'poly':
          // Simulate Post (id=1) comments
          const polyTargetId = 1;
          const polyType = 'Post';
          const polyComments = db.polymorphic_comments.filter(c => c.commentable_id === polyTargetId && c.commentable_type === polyType);
          const polyTable = document.getElementById('poly-table');
          polyTable.innerHTML = `
            <thead><tr><th>Type</th><th>Target ID</th><th>Comment</th></tr></thead>
            <tbody>
              ${polyComments.map(c => `<tr><td>${escapeHTML(c.commentable_type)}</td><td>${c.commentable_id}</td><td>${escapeHTML(c.body)}</td></tr>`).join('')}
            </tbody>
          `;
          document.getElementById('poly-result').style.display = 'block';
          break;

        case 'manypoly':
          // Simulate Post(id=1) tags
          const mpTargetId = 1;
          const mpType = 'Post';
          const relations = db.taggables.filter(t => t.taggable_id === mpTargetId && t.taggable_type === mpType);
          const tags = relations.map(r => db.tags.find(t => t.id === r.tag_id));
          const mpTable = document.getElementById('manypoly-table');
          mpTable.innerHTML = `
            <thead><tr><th>Type</th><th>Target ID</th><th>Tag</th></tr></thead>
            <tbody>
              ${tags.map(t => `<tr><td>${escapeHTML(mpType)}</td><td>${mpTargetId}</td><td>${escapeHTML(t.name)}</td></tr>`).join('')}
            </tbody>
          `;
          document.getElementById('manypoly-result').style.display = 'block';
          break;
      }
    }

    /* small helper to escape HTML for safety in outputs */
    function escapeHTML(str) {
      if (!str && str !== 0) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
    }

    // auto-show first SQL for convenience
    document.addEventListener('DOMContentLoaded', () => {
      showSQL('one2one');
    });
  </script>
</body>
</html>
