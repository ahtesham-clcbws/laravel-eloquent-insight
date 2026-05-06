<?php

namespace EloquentInsight\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectInsightOverlay
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        // Only inject in local environment and for HTML responses
        if (\app()->environment('production') || 
            !\config('insight.enabled', true) || 
            !\config('insight.ui_enabled', true) ||
            !$this->isHtmlResponse($response)) {
            return $response;
        }

        $this->injectOverlay($response);

        return $response;
    }

    /**
     * Check if the response is an HTML response.
     */
    protected function isHtmlResponse($response): bool
    {
        $contentType = $response->headers->get('Content-Type');
        return str_contains((string) $contentType, 'text/html');
    }

    /**
     * Inject the overlay script into the response content.
     */
    protected function injectOverlay($response): void
    {
        $content = $response->getContent();
        $script = $this->getOverlayScript();

        $pos = strripos($content, '</body>');

        if ($pos !== false) {
            $content = substr($content, 0, $pos) . $script . substr($content, $pos);
            $response->setContent($content);
        }
    }

    /**
     * Get the JS overlay script.
     */
    protected function getOverlayScript(): string
    {
        return '
        <div id="eloquent-insight-root"></div>
        <script>
            (function() {
                const root = document.getElementById("eloquent-insight-root");
                const shadow = root.attachShadow({mode: "open"});
                
                const styles = `
                    :host {
                        position: fixed;
                        bottom: 20px;
                        right: 20px;
                        z-index: 999999;
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                    }
                    .trigger {
                        background: #1a202c;
                        color: #6366f1;
                        padding: 10px 15px;
                        border-radius: 8px;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                        cursor: pointer;
                        font-weight: 600;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        border: 1px solid #2d3748;
                        transition: all 0.2s;
                    }
                    .trigger:hover { transform: translateY(-2px); border-color: #6366f1; }
                    .panel {
                        display: none;
                        width: 450px;
                        max-height: 600px;
                        background: #1a202c;
                        border: 1px solid #2d3748;
                        border-radius: 12px;
                        overflow-y: auto;
                        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                        position: absolute;
                        bottom: 60px;
                        right: 0;
                    }
                    .panel.open { display: block; }
                    .header { padding: 15px; border-bottom: 1px solid #2d3748; font-weight: 700; color: #fff; display: flex; justify-content: space-between; }
                    .section-title { padding: 10px 15px; background: #2d3748; color: #a0aec0; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 800; }
                    .insight { padding: 15px; border-bottom: 1px solid #2d3748; }
                    .insight:last-child { border-bottom: none; }
                    .title { color: #f6ad55; font-size: 12px; font-weight: 600; margin-bottom: 5px; display: flex; justify-content: space-between; align-items: center; }
                    .summary { color: #cbd5e0; font-size: 13px; line-height: 1.5; margin-bottom: 10px; }
                    .badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 800; color: white; }
                    .badge.critical { background: #e53e3e; }
                    .badge.high { background: #ed8936; }
                    .badge.moderate { background: #4299e1; }
                    .ghost-item { color: #fc8181; font-size: 12px; margin-bottom: 4px; display: flex; align-items: center; gap: 6px; }
                    .fix-btn {
                        background: #4f46e5;
                        color: white;
                        border: none;
                        padding: 6px 12px;
                        border-radius: 6px;
                        cursor: pointer;
                        font-size: 12px;
                        font-weight: 600;
                        width: 100%;
                    }
                    .fix-btn:hover { background: #4338ca; }
                `;

                const template = `
                    <style>${styles}</style>
                    <div class="panel" id="panel">
                        <div class="header">
                            <span>Eloquent Insight</span>
                            <span id="total-badge" style="background: #e53e3e; padding: 2px 8px; border-radius: 99px; font-size: 10px;">0</span>
                        </div>
                        <div id="content"></div>
                    </div>
                    <div class="trigger" id="trigger">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                        Insights
                    </div>
                `;

                shadow.innerHTML = template;

                const panel = shadow.getElementById("panel");
                const trigger = shadow.getElementById("trigger");
                const content = shadow.getElementById("content");
                const totalBadge = shadow.getElementById("total-badge");

                trigger.onclick = () => {
                    panel.classList.toggle("open");
                    if (panel.classList.contains("open")) fetchData();
                };

                function fetchData() {
                    fetch("/_insight/data")
                        .then(r => r.json())
                        .then(data => {
                            totalBadge.innerText = data.summary.total_solutions;
                            let html = "";

                            // Solutions Section
                            if (data.solutions.length > 0) {
                                html += `<div class="section-title">N+1 Bottlenecks</div>`;
                                html += data.solutions.map(s => `
                                    <div class="insight">
                                        <div class="title">
                                            <span>${s.entry_point}</span>
                                            <span class="badge ${s.impact.toLowerCase()}">${s.impact}</span>
                                        </div>
                                        <div class="summary">${s.action_summary}</div>
                                        <button class="fix-btn" data-file="${s.file}" data-line="${s.line}" data-relations=\'${JSON.stringify(s.relations)}\'>Apply Auto-Fix</button>
                                    </div>
                                `).join("");
                            }

                            // Ghost Relations Section
                            const ghosts = Object.entries(data.efficiency.ghost_relations);
                            if (ghosts.length > 0) {
                                html += `<div class="section-title">Ghost Relations (Unused)</div>`;
                                html += ghosts.map(([model, relations]) => `
                                    <div class="insight">
                                        <div class="title">${model}</div>
                                        <div class="summary">These relations are loaded but never accessed:</div>
                                        ${relations.map(r => `<div class="ghost-item">⚠️ ${r}</div>`).join("")}
                                    </div>
                                `).join("");
                            }

                            // Duplicate Queries Section
                            if (data.duplicates.length > 0) {
                                html += `<div class="section-title">Duplicate Queries</div>`;
                                html += data.duplicates.map(d => `
                                    <div class="insight">
                                        <div class="title">Count: ${d[0].count}</div>
                                        <div class="summary" style="font-family: monospace; font-size: 11px;">${d[0].sql}</div>
                                    </div>
                                `).join("");
                            }

                            content.innerHTML = html || `<div class="insight" style="text-align: center; color: #718096;">No insights detected for this page.</div>`;

                            content.querySelectorAll(".fix-btn").forEach(btn => {
                                btn.onclick = (e) => applyFix(e.target);
                            });
                        });
                }

                function applyFix(btn) {
                    btn.disabled = true;
                    btn.innerText = "Applying...";
                    const csrf = document.querySelector("meta[name=\'csrf-token\']");
                    
                    fetch("/_insight/fix", {
                        method: "POST",
                        headers: { 
                            "Content-Type": "application/json", 
                            "X-CSRF-TOKEN": csrf ? csrf.content : "" 
                        },
                        body: JSON.stringify({
                            file: btn.dataset.file,
                            line: btn.dataset.line,
                            relations: JSON.parse(btn.dataset.relations)
                        })
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.status === "success") {
                            btn.innerText = "✓ Fixed";
                            btn.style.background = "#38a169";
                        } else {
                            btn.innerText = "Error";
                            btn.style.background = "#e53e3e";
                        }
                    });
                }
            })();
        </script>
        ';
    }
}
