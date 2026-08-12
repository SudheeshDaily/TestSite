<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Logger Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a0a0a;
            color: #e0e0e0;
            padding: 20px;
            min-height: 100vh;
        }
        .container { max-width: 1600px; margin: 0 auto; }
        
        h1 {
            text-align: center;
            margin-bottom: 10px;
            font-size: 2.5rem;
            background: linear-gradient(90deg, #00d4ff, #7b2cbf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .subtitle {
            text-align: center;
            color: #888;
            margin-bottom: 30px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid #2a2a4a;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stat-card:hover { transform: translateY(-5px); }
        
        .stat-card h2 {
            font-size: 2.5rem;
            color: #00d4ff;
            margin-bottom: 5px;
        }
        
        .stat-card p { color: #888; font-size: 0.95rem; }
        
        .controls {
            background: #1a1a2e;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            border: 1px solid #2a2a4a;
        }
        
        .controls input, .controls select {
            padding: 12px 18px;
            border: 1px solid #333;
            border-radius: 8px;
            background: #0f0f1a;
            color: #fff;
            font-size: 14px;
            min-width: 200px;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .controls input:focus, .controls select:focus {
            border-color: #00d4ff;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-refresh { background: #00d4ff; color: #000; }
        .btn-refresh:hover { background: #00b8e6; }
        
        .btn-export { background: #7b2cbf; color: white; }
        .btn-export:hover { background: #6a1fad; }
        
        .btn-clear { background: #e63946; color: white; }
        .btn-clear:hover { background: #c92a37; }
        
        .table-container {
            background: #1a1a2e;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #2a2a4a;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        
        thead {
            background: #0f0f1a;
            position: sticky;
            top: 0;
        }
        
        th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: #00d4ff;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #2a2a4a;
        }
        
        td {
            padding: 14px 16px;
            border-bottom: 1px solid #2a2a4a;
            color: #ccc;
        }
        
        tr:hover { background: rgba(0, 212, 255, 0.05); }
        
        .serial {
            font-weight: bold;
            color: #00d4ff;
            font-size: 14px;
        }
        
        .ip-cell {
            font-family: 'Courier New', monospace;
            color: #fff;
            background: rgba(0, 212, 255, 0.1);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .badge-chrome { background: #4285f4; color: white; }
        .badge-firefox { background: #ff7139; color: white; }
        .badge-safari { background: #00d8ff; color: #000; }
        .badge-edge { background: #0078d7; color: white; }
        .badge-other { background: #666; color: white; }
        
        .location-cell { max-width: 150px; }
        
        .time-cell {
            font-family: monospace;
            font-size: 12px;
            color: #888;
        }
        
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #666;
        }
        
        .empty-state h3 {
            color: #888;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }
        
        .loading {
            text-align: center;
            padding: 60px;
            color: #00d4ff;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #2a2a4a;
            border-top-color: #00d4ff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .delete-btn {
            background: #e63946;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .delete-btn:hover { background: #c92a37; }
        
        @media (max-width: 768px) {
            .controls { flex-direction: column; align-items: stretch; }
            .controls input, .controls select { width: 100%; }
            th, td { padding: 10px 8px; font-size: 12px; }
            .table-container { overflow-x: auto; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 IP Logger Dashboard</h1>
        <p class="subtitle">Real-time visitor tracking & analytics</p>
        
        <div class="stats">
            <div class="stat-card">
                <h2 id="totalLogs">0</h2>
                <p>Total Visits</p>
            </div>
            <div class="stat-card">
                <h2 id="uniqueIPs">0</h2>
                <p>Unique IPs</p>
            </div>
            <div class="stat-card">
                <h2 id="topCountry">-</h2>
                <p>Top Country</p>
            </div>
            <div class="stat-card">
                <h2 id="topBrowser">-</h2>
                <p>Top Browser</p>
            </div>
        </div>
        
        <div class="controls">
            <input type="text" id="searchInput" placeholder="🔍 Search IP, country, city...">
            <select id="browserFilter">
                <option value="">All Browsers</option>
                <option value="Chrome">Chrome</option>
                <option value="Firefox">Firefox</option>
                <option value="Safari">Safari</option>
                <option value="Edge">Edge</option>
            </select>
            <button class="btn btn-refresh" onclick="loadLogs()">🔄 Refresh</button>
            <button class="btn btn-export" onclick="exportJSON()">📥 JSON</button>
            <button class="btn btn-export" onclick="exportCSV()">📄 CSV</button>
            <button class="btn btn-clear" onclick="clearAll()">🗑 Clear All</button>
        </div>
        
        <div class="table-container">
            <div id="loadingState" class="loading">
                <div class="spinner"></div>
                <p>Loading logs from Firebase...</p>
            </div>
            
            <table id="logsTable" style="display: none;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date & Time</th>
                        <th>IP Address</th>
                        <th>Location</th>
                        <th>ISP</th>
                        <th>Browser</th>
                        <th>OS</th>
                        <th>Screen</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
            
            <div id="emptyState" class="empty-state" style="display: none;">
                <h3>No logs found</h3>
                <p>Visitor data will appear here once someone visits your logger page</p>
            </div>
        </div>
    </div>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
    
    <script>
        // 🔧 REPLACE WITH YOUR FIREBASE CONFIG
        const firebaseConfig = {
            apiKey: "YOUR_API_KEY",
            authDomain: "YOUR_PROJECT.firebaseapp.com",
            databaseURL: "https://YOUR_PROJECT-default-rtdb.firebaseio.com",
            projectId: "YOUR_PROJECT",
            storageBucket: "YOUR_PROJECT.appspot.com",
            messagingSenderId: "123456789",
            appId: "YOUR_APP_ID"
        };

        firebase.initializeApp(firebaseConfig);
        const database = firebase.database();
        
        let allLogs = [];
        let logsRef = null;

        function getBrowserBadgeClass(browser) {
            const map = {
                'Chrome': 'badge-chrome',
                'Firefox': 'badge-firefox',
                'Safari': 'badge-safari',
                'Edge': 'badge-edge'
            };
            return map[browser] || 'badge-other';
        }

        function formatTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString('en-US', {
                month: 'short',
                day: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function updateStats() {
            document.getElementById('totalLogs').textContent = allLogs.length;
            
            const uniqueIPs = new Set(allLogs.map(l => l.ip)).size;
            document.getElementById('uniqueIPs').textContent = uniqueIPs;

            const countries = {};
            const browsers = {};
            
            allLogs.forEach(log => {
                countries[log.country] = (countries[log.country] || 0) + 1;
                browsers[log.browser] = (browsers[log.browser] || 0) + 1;
            });
            
            const topCountry = Object.entries(countries).sort((a,b) => b[1]-a[1])[0];
            document.getElementById('topCountry').textContent = topCountry ? topCountry[0] : '-';
            
            const topBrowser = Object.entries(browsers).sort((a,b) => b[1]-a[2])[0];
            document.getElementById('topBrowser').textContent = topBrowser ? topBrowser[0] : '-';
        }

        function renderTable(logs = allLogs) {
            const tbody = document.getElementById('tableBody');
            const table = document.getElementById('logsTable');
            const emptyState = document.getElementById('emptyState');
            const loadingState = document.getElementById('loadingState');
            
            loadingState.style.display = 'none';
            tbody.innerHTML = '';

            if (logs.length === 0) {
                table.style.display = 'none';
                emptyState.style.display = 'block';
                return;
            }
            
            table.style.display = 'table';
            emptyState.style.display = 'none';

            logs.forEach((log, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="serial">${index + 1}</td>
                    <td class="time-cell">${formatTime(log.timestamp)}</td>
                    <td><span class="ip-cell">${log.ip || 'N/A'}</span></td>
                    <td class="location-cell">${log.city || 'N/A'}, ${log.country || 'N/A'}</td>
                    <td>${log.isp || 'N/A'}</td>
                    <td><span class="badge ${getBrowserBadgeClass(log.browser)}">${log.browser}</span></td>
                    <td>${log.os || 'N/A'}</td>
                    <td>${log.screen || 'N/A'}</td>
                    <td><button class="delete-btn" onclick="deleteLog('${log.key}')">Delete</button></td>
                `;
                tbody.appendChild(row);
            });
        }

        function loadLogs() {
            document.getElementById('loadingState').style.display = 'block';
            document.getElementById('logsTable').style.display = 'none';
            document.getElementById('emptyState').style.display = 'none';
            
            if (logsRef) logsRef.off();
            
            logsRef = database.ref('logs').orderByChild('timestamp');
            
            logsRef.on('value', (snapshot) => {
                allLogs = [];
                const data = snapshot.val();
                
                if (data) {
                    Object.keys(data).forEach(key => {
                        allLogs.push({ key: key, ...data[key] });
                    });
                    allLogs.reverse();
                }
                
                updateStats();
                renderTable();
            }, (error) => {
                console.error('Firebase error:', error);
                alert('Error loading data. Check your Firebase config.');
            });
        }

        function deleteLog(key) {
            if (confirm('Delete this log entry?')) {
                database.ref('logs/' + key).remove();
            }
        }

        function clearAll() {
            if (confirm('⚠️ WARNING: This will permanently delete ALL logs!\n\nAre you sure?')) {
                database.ref('logs').remove();
            }
        }

        function exportJSON() {
            const dataStr = JSON.stringify(allLogs, null, 2);
            const blob = new Blob([dataStr], {type: 'application/json'});
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `ip-logs-${new Date().toISOString().split('T')[0]}.json`;
            a.click();
        }

        function exportCSV() {
            const headers = ['Serial', 'Timestamp', 'IP', 'City', 'Region', 'Country', 'Postal', 'Latitude', 'Longitude', 'ISP', 'Browser', 'OS', 'Screen', 'Language', 'Timezone', 'UserAgent'];
            const rows = allLogs.map((log, idx) => [
                idx + 1,
                new Date(log.timestamp).toISOString(),
                log.ip,
                log.city,
                log.region,
                log.country,
                log.postal,
                log.latitude,
                log.longitude,
                log.isp,
                log.browser,
                log.os,
                log.screen,
                log.language,
                log.timezone,
                `"${(log.userAgent || '').replace(/"/g, '""')}"`
            ]);
            
            const csv = [headers.join(','), ...rows.map(r => r.join(','))].join('\n');
            const blob = new Blob([csv], {type: 'text/csv'});
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `ip-logs-${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
        }

        // Search & Filter
        document.getElementById('searchInput').addEventListener('input', filterLogs);
        document.getElementById('browserFilter').addEventListener('change', filterLogs);

        function filterLogs() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const browserFilter = document.getElementById('browserFilter').value;
            
            const filtered = allLogs.filter(log => {
                const matchesSearch = !searchTerm || 
                    (log.ip && log.ip.toLowerCase().includes(searchTerm)) ||
                    (log.country && log.country.toLowerCase().includes(searchTerm)) ||
                    (log.city && log.city.toLowerCase().includes(searchTerm)) ||
                    (log.browser && log.browser.toLowerCase().includes(searchTerm));
                
                const matchesBrowser = !browserFilter || log.browser === browserFilter;
                
                return matchesSearch && matchesBrowser;
            });
            
            renderTable(filtered);
        }

        // Auto-refresh every 30 seconds
        setInterval(loadLogs, 30000);
        
        // Load on startup
        window.addEventListener('load', loadLogs);
    </script>
</body>
</html>
