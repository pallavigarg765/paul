<?php
/*
 * Internal Property Tagging
 * Static UI prototype based on the supplied design.
 * Compatible with legacy PHP syntax (no modern PHP features required).
 *
 * Later integration points:
 * - Replace the sample property data with CRM/database values.
 * - Load categories and assigned tags from the database.
 * - Connect Save Changes, notes, photo selection and category actions to CRM endpoints.
 */
$propertyId = 'H2272';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Internal Property Tagging | <?php echo htmlspecialchars($propertyId, ENT_QUOTES, 'UTF-8'); ?></title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">

<style>
:root {
    --green: #183f34;
    --green-2: #245744;
    --green-soft: #e7f5ec;
    --blue-soft: #e8f4f8;
    --line: #dedede;
    --line-light: #ececec;
    --text: #303030;
    --muted: #888;
    --panel: #fff;
    --page: #f4f5f5;
    --shadow: 0 1px 2px rgba(0,0,0,.025);
}
* { box-sizing: border-box; }

html,
body {
    margin: 0;
    min-height: 100%;
    font-family: 'Geist', sans-serif;
    color: var(--text);
    background: var(--page);
    font-size: 13px;
}

*,
*::before,
*::after {
    font-family: 'Geist', sans-serif;
}

button,
input,
select,
textarea {
    font: inherit;
}

button, input, select { font:inherit; }
button { cursor:pointer; }
.topbar {
    height:56px; background:#fff; border-bottom:1px solid #e7e7e7;
    display:flex; align-items:center; justify-content:space-between; padding:0 17px;
    position:sticky; top:0; z-index:20;
}
.brand { display:flex; align-items:center; height:100%; gap:12px; font-size:16px; font-weight:500; }
.back { font-size:23px; line-height:1; color:#555; text-decoration:none; margin-right:1px; }
.top-right { display:flex; align-items:center; gap:10px; }
.search-control { display:flex; height:38px; border:1px solid #dedede; border-radius:10px; overflow:hidden; background:#fff; width:460px; }
.search-control select { width:100px; border:0; border-right:1px solid #e5e5e5; background:#f8f8f8; padding:0 6px; color:#444; outline:none; }
.search-control input { border:0; outline:none; padding:0 12px; flex:1; min-width:0; font-size:14px; }
.search-icon { display:flex; align-items:center; padding:0 11px; color:#777; font-size:18px; }
.shortcut { margin:7px 9px 7px 0; border-radius:5px; background:#f5f5f5; color:#888; padding:3px 6px; font-size:10px; }
.round-action, .avatar { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:0; background:#f4f4f4; color:#555; }
.avatar { background:var(--green); color:#fff; font-size:14px; }
.page { padding:16px 18px 72px; }
.panel { background:var(--panel); border:1px solid var(--line); border-radius:11px; box-shadow:var(--shadow); min-width:0; }
.top-grid { display:grid; grid-template-columns: 1.55fr 1.1fr 1.05fr; gap:14px; margin-bottom:16px; }
.property-photos { padding:13px 14px 12px; }
.property-photos h2 { font-size:20px; margin:0 0 12px; font-weight:600; }
.hero-strip { display:grid; grid-template-columns:repeat(3,1fr); gap:3px; height:168px; border:1px solid #bbb; padding:2px; }
.hero-strip img { width:100%; height:100%; object-fit:cover; min-width:0; }
.panel-heading { display:flex; justify-content:space-between; align-items:center; padding:13px 13px 10px; font-size:15px; font-weight:500; }
.crm-link { color:#328de7; text-decoration:none; font-size:12px; white-space:nowrap; }
.details-table { margin:0 13px 12px; border:1px solid #e2e2e2; }
.detail-row { display:grid; grid-template-columns:112px 1fr; min-height:42px; border-bottom:1px solid #e6e6e6; }
.detail-row:last-child { border-bottom:0; }
.detail-row:nth-child(odd) { background:#f5f5f5; }
.detail-label, .detail-value { padding:11px 10px; }
.detail-label { color:#888; border-right:1px solid #e5e5e5; }
.detail-value { color:#444; }
.notes-panel { overflow:hidden; }
.notes-body { padding:0 12px 10px; max-height:175px; overflow:auto; }
.note { padding:12px 11px 9px; border-bottom:1px solid #e9e9e9; position:relative; min-height:88px; }
.note:first-child { background:#fff9e9; border:1px solid #eee5d2; border-radius:3px; }
.note p { margin:0 20px 9px 0; line-height:1.4; font-size:13px; }
.note-meta { color:#999; font-size:11px; }
.note-menu { position:absolute; right:8px; top:23px; color:#888; border:0; background:transparent; font-size:18px; }
.add-note { border:0; background:transparent; color:#666; font-size:12px; padding:4px 2px; }
.add-note span { font-size:17px; vertical-align:-1px; margin-right:3px; }
.main-grid { display:grid; grid-template-columns: 1.02fr 1.42fr 1.98fr; gap:14px; align-items:stretch; }
.column-panel { min-height:720px; display:flex; flex-direction:column; overflow:hidden; }
.section-title { padding:15px 14px 8px; display:flex; align-items:center; justify-content:space-between; font-size:16px; font-weight:500; }
.small-button { background:#fff; border:1px solid #e4e4e4; border-radius:18px; padding:7px 12px; color:#666; font-size:11px; white-space:nowrap; }
.small-button:hover { background:#f8f8f8; }
.search-box { margin:4px 13px 10px; height:38px; border:1px solid #e0e0e0; border-radius:9px; display:flex; align-items:center; padding:0 11px; gap:8px; color:#888; }
.search-box span { font-size:18px; }
.search-box input { border:0; outline:none; flex:1; min-width:0; color:#444; background:transparent; font-size:12px; }

.pills {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 14px 12px;
}

.pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 29px;
    padding: 5px 13px;
    border: 1px solid #dedede;
    background: #fff;
    color: #666;
    border-radius: 18px;
    font-size: 11px;
    font-weight: 400;
    white-space: nowrap;
    transition: all .15s ease;
}

.pill:hover {
    background: #f7f8f7;
}

.pill.active {
    background: #183f34;
    border-color: #183f34;
    color: #fff;
}

.assigned-list { border-top:1px solid #eee; padding:10px 12px 12px; overflow:auto; flex:1; max-height:555px; }
.category-group { border:1px solid #e7e7e7; border-radius:9px; margin-bottom:10px; overflow:hidden; background:#fff; }
.group-title { width:100%; display:flex; align-items:center; gap:9px; padding:10px 10px; background:#fafafa; border:0; text-align:left; color:#444; font-size:12px; }
.chevron { color:#777; font-size:16px; width:10px; }
.count { background:#f1f1f1; border-radius:4px; padding:2px 5px; color:#777; font-size:10px; }
.group-items { padding:0 8px 7px; }
.assigned-item { display:flex; align-items:center; min-height:31px; border-bottom:1px solid #f0f0f0; padding:3px 3px; gap:6px; font-size:12px; color:#555; }
.assigned-item:last-child { border-bottom:0; }
.remove-tag { border:0; background:transparent; color:#999; font-size:17px; padding:0 3px; }
.new-label { background:#46a9d4; color:#fff; font-size:9px; border-radius:3px; padding:2px 4px; }
.grade { margin-left:auto; border:1px solid #e5e5e5; border-radius:5px; padding:3px 5px; color:#666; font-size:10px; white-space:nowrap; }
.grade .dot { color:#52a76b; font-size:12px; }
.grade.b .dot { color:#e5a13b; }
.grade.c .dot { color:#d65c5c; }
.switch-bar { border-top:1px solid #eee; padding:10px 12px; display:flex; align-items:center; justify-content:space-between; font-size:11px; color:#666; }
.switch-select { border:1px solid #ddd; border-radius:5px; background:#fafafa; padding:4px 6px; color:#555; font-size:10px; }
.switch-all { border:1px solid #e2e2e2; background:#f8f8f8; border-radius:5px; padding:6px 9px; font-size:10px; }
.add-category-panel .section-title { padding-bottom:4px; }
.sub-heading { color:#999; font-size:11px; padding:0 14px 5px; }
.add-search {
    height: 44px;
    margin: 5px 14px 12px;
    border-radius: 10px;
}
.add-search input {
    font-size: 14px;
}
.add-tabs { padding-bottom:11px; }

/* Add Categories browser */
.category-browser {
    display: grid;
    grid-template-columns: 0.92fr 1.08fr;
    border-top: 1px solid #e7e7e7;
    flex: 1;
    min-height: 0;
    overflow: hidden;
}

.category-list,
.subcategory-list {
    display: flex;
    flex-direction: column;
    min-width: 0;
    min-height: 0;
    overflow: hidden;
}

.category-list {
    border-right: 1px solid #e7e7e7;
}

.browser-heading {
    flex: 0 0 auto;
    padding: 13px 14px 10px;
    font-size: 15px;
    font-weight: 500;
    border-bottom: 1px solid #f0f0f0;
    background: #fff;
}

.browser-heading small {
    color: #999;
    font-size: 11px;
    background: #f2f2f2;
    padding: 3px 5px;
    border-radius: 4px;
    margin-left: 3px;
}

.category-items {
    flex: 1;
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 5px 9px 12px;
}

.category-choice {
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-height: 44px;
    padding: 10px 11px;
    margin: 2px 0;
    color: #858585;
    font-size: 14px;
    border-left: 3px solid transparent;
    border-radius: 10px;
    cursor: pointer;
    transition: background .15s ease, color .15s ease;
}

.category-choice:hover {
    background: #f7f8f7;
    color: #444;
}

.category-choice.selected {
    background: #e7f5ec;
    border-left-color: #4aab67;
    color: #303a33;
}

.category-choice span:last-child {
    color: #999;
    font-size: 12px;
    margin-left: 8px;
}

.subcategory-list {
    border-left: 0;
}

.subcategory-head {
    flex: 0 0 auto;
    padding: 12px 12px 4px;

    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;

    background: #ffffff;
    color: #303030;
    font-size: 15px;
    font-weight: 500;
}

.subcategory-head small {
    color: #888888;
    font-size: 10px;
    font-weight: normal;
    white-space: nowrap;
}


.subcategory-desc {
    flex: 0 0 auto;
    padding: 0 12px 10px;

    background: #ffffff;
    color: #999999;
    font-size: 11px;
}

.subcategory-items {
    display: block;
    flex: 1;
    min-height: 0;
    width: 100%;
    padding: 0 !important;
    margin: 0 !important;

    overflow-y: auto;
    overflow-x: hidden;

    background: #ffffff;
    border: 0;
    border-top: 1px solid #e5e5e5;
    border-radius: 0;
}

.subcategory {
    display: flex;
    align-items: center;
    gap: 12px;

    width: 100%;
    min-height: 40px;

    padding: 8px 12px !important;
    margin: 0 !important;

    border: 0;
    border-bottom: 1px solid #e2e2e2;
    border-radius: 0;

    background: #ffffff;
    color: #555555;

    font-size: 14px;
    cursor: pointer;
}

.subcategory:nth-child(even) {
    background: #f3f3f3;
}

.subcategory.checked {
    background: #e7f5ec !important;
    color: #303a33;
}

.subcategory input {
    width: 17px;
    height: 17px;
    min-width: 17px;
    margin: 0 !important;
    accent-color: #4c9b62;
    cursor: pointer;
}


/* Clean, lightweight scrollbars across all scrollable sections */
.assigned-list,
.category-items,
.subcategory-items,
.photo-grid,
.notes-body {
    scrollbar-width: thin;
    scrollbar-color: #d6d6d6 transparent;
}

/* Chrome, Edge, Safari */
.assigned-list::-webkit-scrollbar,
.category-items::-webkit-scrollbar,
.subcategory-items::-webkit-scrollbar,
.photo-grid::-webkit-scrollbar,
.notes-body::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.assigned-list::-webkit-scrollbar-track,
.category-items::-webkit-scrollbar-track,
.subcategory-items::-webkit-scrollbar-track,
.photo-grid::-webkit-scrollbar-track,
.notes-body::-webkit-scrollbar-track {
    background: transparent;
}

.assigned-list::-webkit-scrollbar-thumb,
.category-items::-webkit-scrollbar-thumb,
.subcategory-items::-webkit-scrollbar-thumb,
.photo-grid::-webkit-scrollbar-thumb,
.notes-body::-webkit-scrollbar-thumb {
    background: #d6d6d6;
    border-radius: 10px;
}

.assigned-list::-webkit-scrollbar-thumb:hover,
.category-items::-webkit-scrollbar-thumb:hover,
.subcategory-items::-webkit-scrollbar-thumb:hover,
.photo-grid::-webkit-scrollbar-thumb:hover,
.notes-body::-webkit-scrollbar-thumb:hover {
    background: #bdbdbd;
}

/* Remove native scrollbar arrow buttons */
.assigned-list::-webkit-scrollbar-button,
.category-items::-webkit-scrollbar-button,
.subcategory-items::-webkit-scrollbar-button,
.photo-grid::-webkit-scrollbar-button,
.notes-body::-webkit-scrollbar-button {
    display: none;
    width: 0;
    height: 0;
}



.gallery-panel { min-height:720px; }
.gallery-header { display:flex; justify-content:space-between; align-items:center; gap:10px; padding:13px 13px 10px; border-bottom:1px solid #eee; }
.gallery-title { font-size:16px; font-weight:500; white-space:nowrap; }
.photo-count { font-size:10px; color:#888; background:#f1f1f1; border-radius:4px; padding:3px 5px; vertical-align:2px; margin-left:3px; }
.collections { display:flex; align-items:center; gap:11px; }
.collection { display:flex; flex-direction:column; align-items:center; gap:3px; font-size:10px; color:#777; }
.collection input { accent-color:var(--green); width:14px; height:14px; margin:0; }
.gallery-content {
    position: relative;
    height: 565px;
    min-height: 565px;
    overflow: hidden;
}
.photo-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:9px; height: 100%; max-height: none; overflow:auto; padding-bottom:20px; }
.photo { position:relative; height:164px; border:1px solid #e5e5e5; background:#eee; cursor:pointer; overflow:hidden; }
.photo img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .2s ease; }
.photo:hover img { transform:scale(1.025); }
.photo.selected:after { content:"✓"; position:absolute; right:8px; top:8px; width:22px; height:22px; border-radius:50%; background:var(--green); color:#fff; display:flex; align-items:center; justify-content:center; font-size:13px; box-shadow:0 1px 4px #5556; }
.gallery-footer { display:flex; justify-content:space-between; align-items:center; padding:10px 13px 12px; border-top:1px solid #eee; color:#666; font-size:11px; gap:8px; }
.pagination { display:flex; align-items:center; gap:5px; }
.page-btn { min-width:27px; height:27px; border:1px solid #e1e1e1; background:#fff; border-radius:6px; color:#666; font-size:11px; }
.page-btn.active { background:var(--green); color:#fff; border-color:var(--green); }
.per-page { border:1px solid #e1e1e1; border-radius:6px; padding:6px 8px; color:#666; background:#fff; font-size:11px; }
.bottom-bar { position:fixed; bottom:0; left:0; right:0; height:58px; background:#fff; border-top:1px solid #e6e6e6; display:flex; align-items:center; justify-content:space-between; padding:0 18px; z-index:15; }
.last-updated { color:#888; font-size:12px; }
.last-updated span { margin-right:6px; }
.bottom-actions { display:flex; gap:9px; align-items:center; }
.more-btn { border:1px solid #e3e3e3; border-radius:8px; background:#f8f8f8; width:39px; height:36px; color:#777; }
.save-btn { border:0; background:var(--green); color:#fff; border-radius:8px; padding:11px 22px; font-weight:600; font-size:12px; }
.save-btn:hover { background:#245544; }
.toast { display:none; position:fixed; right:20px; bottom:75px; background:#183f34; color:#fff; padding:12px 17px; border-radius:8px; box-shadow:0 4px 15px #0002; z-index:40; }
.modal-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:50; align-items:center; justify-content:center; padding:20px; }
.modal { background:#fff; width:100%; max-width:440px; border-radius:12px; padding:20px; box-shadow:0 10px 40px #0003; }
.modal h3 { margin:0 0 15px; font-size:17px; }
.modal input, .modal textarea { width:100%; border:1px solid #ddd; border-radius:7px; padding:10px; margin-bottom:12px; outline:none; }
.modal textarea { min-height:90px; resize:vertical; }
.modal-actions { display:flex; justify-content:flex-end; gap:8px; }
.modal-actions button { padding:8px 14px; border-radius:6px; border:1px solid #ddd; background:#fff; }
.modal-actions .primary { background:var(--green); color:#fff; border-color:var(--green); }
@media (max-width:1250px) {
    .top-grid { grid-template-columns:1.2fr 1fr; }
    .notes-panel { grid-column:1 / -1; }
    .main-grid { grid-template-columns:1fr 1.35fr; }
    .gallery-panel { grid-column:1 / -1; }
    .column-panel, .gallery-panel { min-height:650px; }
}
@media (max-width:760px) {
    .topbar { padding:0 10px; }
    .brand { font-size:13px; }
    .search-control { width:min(48vw,300px); }
    .search-control select { width:85px; }
    .round-action { display:none; }
    .page { padding:10px 9px 72px; }
    .top-grid, .main-grid { grid-template-columns:1fr; gap:10px; }
    .notes-panel, .gallery-panel { grid-column:auto; }
    .hero-strip { height:130px; }
    .column-panel, .gallery-panel { min-height:550px; }
    .photo-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    .photo { height:145px; }
    .category-browser {
    display: block;
    overflow: visible;
}
    .collections { gap:5px; }
    .collection { font-size:9px; }
    .gallery-header { align-items:flex-start; flex-direction:column; }
    .gallery-footer { flex-wrap:wrap; }
    .last-updated { font-size:10px; }
    .save-btn { padding:10px 14px; }
}

/* Theme + responsive Figma layouts */
.mobile-property-summary,.mobile-tabs,.mobile-add-category-trigger { display:none; }
body.dark-theme { background:#202020; color:#f1f1f1; --page:#202020; --panel:#292929; --line:#414141; --line-light:#383838; --text:#f1f1f1; --muted:#aaa; }
body.dark-theme .topbar, body.dark-theme .bottom-bar { background:#292929; border-color:#414141; color:#f2f2f2; }
body.dark-theme .panel { background:#292929; border-color:#414141; }
body.dark-theme .search-control, body.dark-theme .search-box, body.dark-theme .search-control input,
body.dark-theme .search-control select, body.dark-theme .search-box input { background:#202020; color:#eee; border-color:#444; }
body.dark-theme .search-control select { border-right-color:#444; }
body.dark-theme .detail-row, body.dark-theme .detail-row:nth-child(odd) { background:#292929; border-color:#414141; }
body.dark-theme .detail-label { border-color:#414141; color:#999; }
body.dark-theme .detail-value, body.dark-theme .panel-heading, body.dark-theme .section-title,
body.dark-theme .gallery-title, body.dark-theme .browser-heading, body.dark-theme .subcategory-head { color:#eee; }
body.dark-theme .note { background:#242424; border-color:#414141; color:#ddd; }
body.dark-theme .note:first-child { background:#fff8e8; color:#333; }
body.dark-theme .note:first-child .note-meta { color:#888; }
body.dark-theme .group-title, body.dark-theme .category-group { background:#202020; border-color:#414141; color:#eee; }
body.dark-theme .assigned-item, body.dark-theme .subcategory, body.dark-theme .subcategory:nth-child(even) { background:#292929; border-color:#414141; color:#bbb; }
body.dark-theme .subcategory.checked { background:#e7f5ec; color:#333; }
body.dark-theme .category-choice { color:#aaa; }
body.dark-theme .category-choice.selected { background:#e7f5ec; color:#333; }
body.dark-theme .small-button, body.dark-theme .pill, body.dark-theme .page-btn, body.dark-theme .per-page,
body.dark-theme .switch-select, body.dark-theme .switch-all, body.dark-theme .more-btn { background:#202020; color:#ddd; border-color:#484848; }
body.dark-theme .pill.active, body.dark-theme .page-btn.active { background:var(--green); color:#fff; border-color:var(--green); }
body.dark-theme .search-box { border-color:#444; }
body.dark-theme .category-browser, body.dark-theme .category-list, body.dark-theme .gallery-header,
body.dark-theme .gallery-footer, body.dark-theme .switch-bar { border-color:#414141; }
body.dark-theme .photo { border-color:#444; }
body.dark-theme .round-action { background:#202020; color:#eee; }
body.dark-theme .mobile-property-summary, body.dark-theme .mobile-tabs { background:#292929; border-color:#414141; }
body.dark-theme .mobile-tab { color:#bbb; }
body.dark-theme .mobile-tab.active { color:#fff; border-bottom-color:#6dbb8c; }
body.dark-theme .mobile-add-category-trigger { background:#292929; color:#eee; border-color:#414141; }

body.dark-theme .lightbox {
    background: rgba(0, 0, 0, 0.62);
}

body.dark-theme .lightbox-dialog {
    background: #292929;
    color: #f1f1f1;
    border-color: #777;
}

body.dark-theme .lightbox-thumbnails {
    border-color: #444;
}

body.dark-theme .lightbox-thumbnails img { border-color:#555; }
.lightbox {
    display: none;
    position: absolute;
    inset: 0;
    z-index: 10;

    align-items: center;
    justify-content: center;

    padding: 22px;
    background: rgba(255, 255, 255, 0.58);
}
.lightbox.open { display:flex; }

.lightbox-dialog {
    width: 94%;
    max-width: 980px;

    height: 480px;
    max-height: calc(100% - 32px);

    background: #292929;
    color: #f1f1f1;

    border: 1px solid #777;
    border-radius: 17px;

    padding: 14px 16px 12px;

    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35);

    display: flex;
    flex-direction: column;
    overflow: hidden;
}


.lightbox-top {
    display: flex;
    justify-content: space-between;
    align-items: center;

    flex: 0 0 auto;
    font-size: 12px;
    padding: 0 2px 12px;
}
.lightbox-close {
    border: 0;
    background: transparent;
    color: #999;

    font-size: 27px;
    line-height: 1;
    cursor: pointer;
}

.lightbox-stage {
    position: relative;

    flex: 1 1 auto;
    min-height: 0;
    height: 100%;

    display: flex;
    align-items: center;
    justify-content: center;

    overflow: hidden;
}

.lightbox-stage img {
    display: block;

    width: auto;
    height: auto;

    max-width: 75%;
    max-height: 100%;

    object-fit: contain;
}
.lightbox-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);

    width: 36px;
    height: 36px;

    border: 0;
    border-radius: 50%;

    background: rgba(75, 75, 75, 0.75);
    color: #fff;

    font-size: 27px;
    display: flex;
    align-items: center;
    justify-content: center;

    cursor: pointer;
    z-index: 2;
}

.lightbox-arrow:hover {
    background: rgba(100, 100, 100, 0.95);
}

.lightbox-arrow.prev {
    left: 8px;
}

.lightbox-arrow.next {
    right: 8px;
}

.lightbox-thumbnails {
    display: flex;
    align-items: center;
    gap: 7px;

    flex: 0 0 auto;

    overflow-x: auto;
    overflow-y: hidden;

    padding: 10px 0 2px;
    margin-top: 10px;

    border-top: 1px solid #444;
}
.lightbox-thumbnails img {
    width: 88px;
    height: 60px;

    flex: 0 0 auto;

    object-fit: cover;

    border: 2px solid transparent;
    border-radius: 3px;

    cursor: pointer;
    opacity: 0.75;
}

.lightbox-thumbnails img.active {
    border-color: #52a76b;
    opacity: 1;
}
body.dark-theme .browser-heading,
body.dark-theme .subcategory-head,
body.dark-theme .subcategory-desc {
    background: #292929;
    color: #eee;
}

body.dark-theme .category-items,
body.dark-theme .subcategory-items {
    background: #292929;
    border-color: #414141;
}

body.dark-theme .category-choice:hover {
    background: #343434;
    color: #eee;
}

body.dark-theme .category-choice.selected,
body.dark-theme .subcategory.checked {
    background: #e7f5ec;
    color: #333;
}

body.dark-theme .subcategory {
    background: #292929;
    border-color: #414141;
    color: #ddd;
}

body.dark-theme .subcategory:nth-child(even) {
    background: #252525;
}

body.dark-theme .category-items,
body.dark-theme .subcategory-items {
    scrollbar-color: #555 transparent;
}

body.dark-theme .category-items::-webkit-scrollbar-thumb,
body.dark-theme .subcategory-items::-webkit-scrollbar-thumb {
    background: #555;
}
.mobile-add-category-trigger { width:calc(100% - 24px); margin:12px 12px 0; border:1px solid #e4e4e4; border-radius:9px; background:#fff; color:#555; padding:10px; }
.mobile-add-category-pane .section-title { display:flex; }
.mobile-add-category-pane .category-browser { min-height:0; }
.mobile-add-category-pane .category-list { max-height:570px; }
@media (max-width:760px) {
    .topbar { height:53px; position:relative; }
    .topbar .brand { font-size:14px; }
    .topbar .search-control { position:absolute; top:63px; left:12px; right:12px; width:auto; height:40px; z-index:2; }
    .topbar .top-right { gap:7px; }
    .topbar .round-action,.topbar .avatar { width:30px; height:30px; }
    .mobile-property-summary { display:flex; align-items:center; gap:9px; padding:58px 11px 8px; background:#fff; }
    .mobile-property-thumb { width:39px; height:37px; border-radius:5px; overflow:hidden; flex:0 0 auto; }
    .mobile-property-thumb img { width:100%; height:100%; object-fit:cover; }
    .mobile-property-copy { min-width:0; display:flex; flex-direction:column; gap:3px; }
    .mobile-property-copy strong { font-size:14px; }
    .mobile-property-copy span { font-size:10px; color:#777; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .mobile-photo-count { margin-left:auto; font-size:10px; color:#777; white-space:nowrap; }
    .mobile-tabs { display:flex; height:43px; background:#fff; border-bottom:1px solid #ddd; }
    .mobile-tab { flex:1; border:0; background:transparent; color:#666; font-size:12px; border-bottom:2px solid transparent; }
    .mobile-tab.active { color:#183f34; border-bottom-color:#183f34; font-weight:600; }
    .page { padding:0 10px 74px; }
    .top-grid,.main-grid { display:block; margin:0; }
    .top-grid > .panel { margin:10px 0; }
    .main-grid > .panel { margin:10px 0; min-height:0; }
    body.mobile-tab-overview .main-grid { display:none; }
    body.mobile-tab-categories .top-grid { display:none; }
    body.mobile-tab-categories .gallery-panel { display:none; }
    body.mobile-tab-categories .add-category-panel { display:none; }
    body.mobile-tab-categories .assigned-panel { display:flex; }
    body.mobile-tab-photos .top-grid, body.mobile-tab-photos .assigned-panel,
    body.mobile-tab-photos .add-category-panel { display:none; }
    body.mobile-tab-photos .gallery-panel { display:flex; }
    body.mobile-add-categories .top-grid, body.mobile-add-categories .assigned-panel,
    body.mobile-add-categories .gallery-panel { display:none; }
    body.mobile-add-categories .add-category-panel { display:flex; }
    .property-photos { padding:12px; }
    .property-photos h2 { font-size:19px; }
    .hero-strip { height:112px; }
    .top-grid .panel:nth-child(2) .panel-heading { padding-bottom:10px; }
    .notes-body { max-height:none; }
    .column-panel { min-height:0; }
    .assigned-list { max-height:none; overflow:visible; }
    .mobile-add-category-trigger { display:block; }
    .assigned-panel .section-title { padding-top:14px; }
    .add-category-panel .section-title { padding:14px 12px 5px; }
    .add-category-panel .sub-heading { padding:0 12px 8px; }
    .category-browser { display:block; border-top:0; }
    .category-list {
    display: flex;
    flex-direction: column;
    max-height: none;
    overflow: visible;
}
.category-items {
    flex: initial;
    min-height: auto;
    overflow: visible;
}
    .category-list .browser-heading { padding:12px; }
    .category-choice { min-height:44px; padding:11px 12px; border-bottom:1px solid #e8e8e8; }
    .category-choice:after { content:'›'; color:#777; font-size:20px; margin-left:8px; }
    .category-choice span:last-child { margin-left:auto; }
    .subcategory-list {
    display: none;
    overflow: visible;
}
.subcategory-items {
    flex: initial;
    min-height: auto;
    overflow: visible;
}
    .add-category-panel.mobile-show-subcategories .category-list { display:none; }
    .add-category-panel.mobile-show-subcategories .subcategory-list {
    display: flex;
    flex-direction: column;
}
    .add-category-panel.mobile-show-subcategories .mobile-category-back { display:block; }
    .add-category-panel.mobile-show-subcategories .subcategory { min-height:39px; padding:10px 12px; }
    .add-category-panel.mobile-show-subcategories .subcategory-head { font-size:17px; padding:14px 12px 8px; }
    .add-category-panel.mobile-show-subcategories .subcategory-desc { font-size:12px; padding-bottom:12px; }
    .gallery-panel { min-height:0; }
    .gallery-header { display:block; padding:14px 2px 10px; border:0; }
    .gallery-title { font-size:17px; }
    .collections { display:grid; grid-template-columns:repeat(5,1fr); gap:7px; margin-top:12px; padding:10px; border:1px solid #e5e5e5; border-radius:9px; }
    .collection { flex-direction:row; justify-content:center; gap:4px; padding:7px 2px; background:#f7f7f7; border:1px solid #e7e7e7; border-radius:6px; font-size:10px; }
    .collection:has(input:checked) { background:#e7f5ec; border-color:#d2e9da; }
    .gallery-content {
        position: relative;
        overflow: hidden;
    }

    .photo-grid { grid-template-columns:repeat(2,minmax(0,1fr)); gap:8px; max-height:none; overflow:visible; }
    .photo { height:auto; aspect-ratio:1.15 / 1; }
    .gallery-footer { margin-top:12px; padding:12px 10px; border:1px solid #e5e5e5; border-radius:10px; display:grid; grid-template-columns:1fr auto; gap:12px; }
    .gallery-footer > span { grid-column:1 / -1; }
    .pagination { grid-column:1 / -1; justify-content:space-between; }
    .per-page { grid-column:2; grid-row:1; }
    .bottom-bar { height:64px; padding:0 10px; }
    .bottom-actions { gap:7px; }
    .save-btn { flex:1; min-width:0; }
    .bottom-bar .last-updated { font-size:9px; max-width:42%; }
    .lightbox { padding:10px; }
    .lightbox-dialog {
        width: 100%;
        max-height: calc(100% - 10px);
        padding: 11px;
        border-radius: 12px;
    }
    .lightbox-stage {
        min-height: 0;
    }

    .lightbox-stage img {
        max-width: 100%;
        max-height: 48vh;
        object-fit: contain;
    }
    .lightbox-arrow {
        width: 30px;
        height: 30px;
        font-size: 22px;
    }

    .lightbox-thumbnails img {
        width: 76px;
        height: 54px;
    }
    body.mobile-add-categories .bottom-bar { display:flex; }
}
@media (max-width:380px) {
    .collections { gap:4px; padding:7px; }
    .collection { font-size:9px; }
    .photo { aspect-ratio:1 / 1; }
    .mobile-property-copy span { max-width:205px; }
}



/* ==========================================================
   FIGMA ALIGNMENT — ASSIGNED CATEGORIES + ADD CATEGORIES
   These rules intentionally override the earlier prototype CSS.
   ========================================================== */

/* Categories on this Property */
.assigned-panel {
    border-radius: 12px;
}

.assigned-panel > .section-title {
    padding: 18px 18px 12px;
    min-height: 58px;
    font-size: 16px;
    font-weight: 600;
}

.assigned-panel > .section-title .small-button {
    min-height: 34px;
    padding: 7px 13px;
    border-radius: 20px;
    font-size: 12px;
    background: #fff;
}

.assigned-panel > .search-box {
    height: 42px;
    margin: 2px 18px 12px;
    padding: 0 12px;
    border-radius: 10px;
    background: #f5f5f5;
}

.assigned-panel > .search-box input {
    font-size: 13px;
}

.assigned-panel > .pills {
    gap: 9px;
    padding: 0 18px 14px;
}

.assigned-panel > .pills .pill {
    min-height: 31px;
    padding: 5px 14px;
    font-size: 12px;
    border-radius: 18px;
}

.assigned-list {
    border-top: 1px solid #e5e5e5;
    padding: 14px 14px 12px;
    max-height: none;
    scrollbar-width: thin;
    scrollbar-color: #dedede transparent;
}

.category-group {
    margin: 0 0 12px;
    padding: 8px 10px 10px;
    border: 0;
    border-radius: 14px;
    background: #f2f2f2;
    overflow: hidden;
}

.category-group:last-child {
    margin-bottom: 0;
}

.group-title {
    min-height: 38px;
    padding: 4px 7px 10px;
    gap: 10px;
    background: transparent;
    color: #383838;
    font-size: 14px;
    font-weight: 500;
}

.group-title:hover {
    background: transparent;
}

.group-title .chevron {
    width: 13px;
    font-size: 19px;
    line-height: 1;
    color: #666;
}

.group-title .count {
    padding: 3px 6px;
    border-radius: 5px;
    background: #e8e8e8;
    color: #666;
    font-size: 10px;
}

.group-items {
    padding: 0;
    border: 1px solid #dedede;
    border-radius: 12px;
    background: #fff;
    overflow: hidden;
}

.assigned-item {
    min-height: 39px;
    padding: 5px 10px;
    gap: 9px;
    border-bottom: 1px solid #e2e2e2;
    background: #fff;
    color: #505050;
    font-size: 13px;
}

.assigned-item:last-child {
    border-bottom: 0;
}

.assigned-item.is-new {
    background: #e8f4f8;
    border-bottom-color: #d5e8ee;
}

.remove-tag {
    width: 17px;
    min-width: 17px;
    padding: 0;
    color: #858585;
    font-size: 18px;
    line-height: 1;
}

.new-label {
    padding: 2px 5px;
    border-radius: 3px;
    background: #4aa5cf;
    color: #fff;
    font-size: 10px;
    line-height: 1.3;
}

.grade {
    min-width: 55px;
    margin-left: auto;
    padding: 3px 6px;
    border: 1px solid #dedede;
    border-radius: 7px;
    background: #f7f7f7;
    color: #555;
    font-size: 11px;
    text-align: center;
}

.grade .dot {
    font-size: 12px;
}

.switch-bar {
    min-height: 48px;
    padding: 9px 14px;
    background: #fff;
}

/* Add Categories header and search controls */
.add-category-panel {
    border-radius: 12px;
}

.add-category-panel > .section-title {
    padding: 16px 16px 2px;
    min-height: 43px;
    font-size: 16px;
    font-weight: 600;
}

.add-category-panel > .section-title .small-button {
    min-height: 34px;
    padding: 7px 13px;
    border-radius: 20px;
    font-size: 12px;
}

.add-category-panel > .sub-heading {
    padding: 0 16px 7px;
    font-size: 12px;
    color: #929292;
}

.add-category-panel > .search-box.add-search {
    height: 42px;
    margin: 5px 16px 11px;
    padding: 0 12px;
    border-radius: 10px;
    background: #f5f5f5;
}

.add-category-panel > .search-box.add-search input {
    font-size: 13px;
}

.add-category-panel > .pills.add-tabs {
    gap: 9px;
    padding: 0 16px 12px;
}

.add-category-panel > .pills.add-tabs .pill {
    min-height: 31px;
    padding: 5px 14px;
    font-size: 12px;
}

/* Two-column category browser */
.category-browser {
    grid-template-columns: 1fr 1.08fr;
    border-top: 1px solid #dedede;
    background: #fff;
}

.category-list {
    border-right: 1px solid #dedede;
    background: #fff;
}

.browser-heading {
    padding: 14px 10px 10px;
    min-height: 38px;
    border-bottom: 0;
    background: #fff;
    font-size: 15px;
    font-weight: 600;
}

.browser-heading small {
    font-size: 11px;
    padding: 3px 6px;
    background: #f1f1f1;
}

.category-items {
    padding: 0 10px 14px;
    scrollbar-width: thin;
    scrollbar-color: #dedede transparent;
}

.category-choice {
    min-height: 38px;
    margin: 1px 0;
    padding: 9px 11px;
    border-left: 3px solid transparent;
    border-radius: 10px;
    font-size: 13px;
    color: #898989;
}

.category-choice:hover {
    background: #f7f7f7;
    color: #444;
}

.category-choice.selected {
    background: #e7f5ec;
    border-left-color: #4a9d60;
    color: #303a33;
    font-weight: 500;
}

.category-choice span:last-child {
    font-size: 12px;
    color: #888;
}

/* Subcategory panel: light-grey canvas with an inset, rounded row stack */
.subcategory-list {
    background: #f4f4f4;
}

.subcategory-head {
    padding: 14px 10px 2px;
    background: #f4f4f4;
    color: #353535;
    font-size: 16px;
    font-weight: 600;
}

.subcategory-head small {
    color: #666;
    font-size: 11px;
}

.subcategory-desc {
    padding: 0 10px 12px;
    background: #f4f4f4;
    color: #929292;
    font-size: 12px;
}

.subcategory-items {
    display: block;
    flex: 1 1 auto;
    min-height: 0;
    margin: 0 18px 16px !important;
    padding: 0 !important;
    border: 1px solid #dedede;
    border-radius: 11px;
    background: #fff;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: #dedede transparent;
}

.subcategory {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    min-height: 43px;
    margin: 0 !important;
    padding: 8px 12px !important;
    border: 0;
    border-bottom: 1px solid #dedede;
    border-radius: 0;
    background: #fff;
    color: #555;
    font-size: 14px;
    line-height: 1.25;
    cursor: pointer;
}

.subcategory:nth-child(even) {
    background: #f3f3f3;
}

.subcategory:last-child {
    border-bottom: 0;
}

.subcategory.checked {
    background: #e7f5ec !important;
    color: #303a33;
}

.subcategory input {
    appearance: none;
    -webkit-appearance: none;
    width: 18px;
    height: 18px;
    min-width: 18px;
    margin: 0 !important;
    border: 1.5px solid #777;
    border-radius: 3px;
    background: transparent;
    display: inline-grid;
    place-content: center;
    cursor: pointer;
}

.subcategory input:checked {
    border-color: #4d9b60;
    background: #4d9b60;
}

.subcategory input:checked::before {
    content: "";
    width: 8px;
    height: 4px;
    border: solid #fff;
    border-width: 0 0 2px 2px;
    transform: rotate(-45deg);
    margin-top: -2px;
}

/* Keep the assigned and category panels consistent in dark mode. */
body.dark-theme .assigned-panel > .section-title .small-button,
body.dark-theme .switch-bar {
    background: #292929;
    color: #ddd;
    border-color: #484848;
}

body.dark-theme .assigned-panel > .search-box,
body.dark-theme .add-category-panel > .search-box.add-search {
    background: #202020;
    border-color: #444;
}

body.dark-theme .assigned-list {
    border-color: #414141;
    scrollbar-color: #555 transparent;
}

body.dark-theme .category-group {
    background: #242424;
}

body.dark-theme .group-title {
    background: transparent;
    color: #eee;
}

body.dark-theme .group-title .count {
    background: #383838;
    color: #bbb;
}

body.dark-theme .group-items {
    background: #292929;
    border-color: #414141;
}

body.dark-theme .assigned-item {
    background: #292929;
    color: #ddd;
    border-color: #414141;
}

body.dark-theme .assigned-item.is-new {
    background: #243b43;
    border-color: #36515a;
}

body.dark-theme .grade {
    background: #202020;
    border-color: #484848;
    color: #ddd;
}

body.dark-theme .category-browser,
body.dark-theme .category-list {
    background: #292929;
    border-color: #414141;
}

body.dark-theme .browser-heading,
body.dark-theme .category-items {
    background: #292929;
    color: #eee;
}

body.dark-theme .category-choice {
    color: #aaa;
}

body.dark-theme .category-choice:hover {
    background: #343434;
    color: #eee;
}

body.dark-theme .category-choice.selected {
    background: #e7f5ec;
    color: #303a33;
}

body.dark-theme .subcategory-list,
body.dark-theme .subcategory-head,
body.dark-theme .subcategory-desc {
    background: #242424;
}

body.dark-theme .subcategory-head {
    color: #eee;
}

body.dark-theme .subcategory-items {
    background: #292929;
    border-color: #414141;
    scrollbar-color: #555 transparent;
}

body.dark-theme .subcategory {
    background: #292929;
    color: #ddd;
    border-color: #414141;
}

body.dark-theme .subcategory:nth-child(even) {
    background: #252525;
}

body.dark-theme .subcategory.checked {
    background: #e7f5ec !important;
    color: #303a33;
}

/* Mobile layout remains stacked and keeps its existing navigation behavior. */
@media (max-width: 760px) {
    .assigned-panel > .section-title {
        padding: 14px 12px 8px;
    }
    .assigned-panel > .search-box {
        margin: 2px 12px 10px;
    }
    .assigned-panel > .pills {
        padding: 0 12px 12px;
    }
    .assigned-list {
        padding: 12px;
    }
    .category-group {
        margin-bottom: 10px;
        padding: 7px 8px 9px;
    }
    .group-title {
        min-height: 36px;
        font-size: 13px;
    }
    .assigned-item {
        min-height: 38px;
        font-size: 13px;
    }
    .add-category-panel > .section-title {
        padding: 14px 12px 5px;
    }
    .add-category-panel > .sub-heading {
        padding: 0 12px 8px;
    }
    .add-category-panel > .search-box.add-search {
        margin: 5px 12px 10px;
    }
    .add-category-panel > .pills.add-tabs {
        padding: 0 12px 11px;
    }
    .category-browser {
        display: block;
        overflow: visible;
    }
    .category-list {
        border-right: 0;
    }
    .category-items {
        padding: 0 10px 12px;
    }
    .subcategory-list {
        background: #f4f4f4;
    }
    .subcategory-head {
        padding: 14px 14px 3px;
    }
    .subcategory-desc {
        padding: 0 14px 10px;
    }
    .subcategory-items {
        margin: 0 14px 14px !important;
        max-height: none;
    }
    .add-category-panel.mobile-show-subcategories .subcategory-list {
        display: flex;
    }
}

@media (prefers-reduced-motion: no-preference) {
    .category-choice,
    .subcategory,
    .assigned-item {
        transition: background-color .12s ease, color .12s ease;
    }
}



/* ==========================================================
   FINAL FIX — INSET SUBCATEGORY LIST ON BOTH SIDES
   The previous width:100% combined with horizontal margins
   made the list overflow its parent on the right.
   ========================================================== */
.subcategory-list {
    min-width: 0;
    overflow: hidden;
}

.subcategory-items {
    width: auto !important;
    max-width: none;
    min-width: 0;
    flex: 1 1 auto;
    margin-left: 10px !important;
    margin-right: 10px !important;
    align-self: stretch;
    box-sizing: border-box;
}

@media (max-width: 760px) {
    .subcategory-items {
        width: auto !important;
        margin-left: 14px !important;
        margin-right: 14px !important;
    }
}



</style>
</head>
<body>
<header class="topbar">
    <div class="brand"><a href="#" class="back" aria-label="Back">&#8592;</a><span>Internal Property Tagging</span></div>
    <div class="top-right">
        <div class="search-control">
            <select aria-label="Search type"><option>Property ID</option><option>Address</option></select>
            <span class="search-icon">&#9906;</span>
            <input type="text" value="<?php echo htmlspecialchars($propertyId, ENT_QUOTES, 'UTF-8'); ?>" aria-label="Search property">
            <span class="shortcut">&#8984;</span>
        </div>
        <button class="round-action" id="themeToggle" type="button" title="Toggle dark theme" aria-label="Toggle dark theme">&#9790;</button>
        <div class="avatar">B</div>
    </div>
</header>

<main class="page">
    <section class="top-grid mobile-overview-pane" data-pane="overview">
        <div class="panel property-photos">
            <h2><?php echo htmlspecialchars($propertyId, ENT_QUOTES, 'UTF-8'); ?></h2>
            <div class="hero-strip">
                <img src="data:image/jpeg;base64,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" alt="Property exterior">
                <img src="data:image/jpeg;base64,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" alt="Property rear and pool">
                <img src="data:image/jpeg;base64,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" alt="Property side exterior">
            </div>
        </div>
        <div class="panel">
            <div class="panel-heading"><span>Property Details</span><a class="crm-link" href="#">View in CRM &#8599;</a></div>
            <div class="details-table">
                <div class="detail-row"><div class="detail-label">Property ID</div><div class="detail-value">H2272</div></div>
                <div class="detail-row"><div class="detail-label">Address</div><div class="detail-value">1642 Meadowbrook Ave Los Angeles, CA 90019</div></div>
                <div class="detail-row"><div class="detail-label">Date Created</div><div class="detail-value">12/06/2025</div></div>
                <div class="detail-row"><div class="detail-label">Film Permit</div><div class="detail-value">Los Angeles</div></div>
            </div>
        </div>
        <div class="panel notes-panel">
            <div class="panel-heading"><span>Property Notes</span><button class="add-note" type="button" onclick="openModal('noteModal')"><span>＋</span> Add Notes</button></div>
            <div class="notes-body" id="notesBody">
                <div class="note"><p>Great exterior for lifestyle shots, Pool and outdoor area look ideal for daytime filming</p><div class="note-meta">Ben H &bull; 12/06/2025, 04:30 AM</div><button class="note-menu" type="button">&#8942;</button></div>
                <div class="note"><p>Check street parking availability for large crew.</p><div class="note-meta">Paul Z &bull; 12/06/2025, 04:30 AM</div><button class="note-menu" type="button">&#8942;</button></div>
                <div class="note"><p>Unique architectural features, ideal for dynamic camera angles.</p><div class="note-meta">Ben H &bull; 12/06/2025, 04:30 AM</div></div>
            </div>
        </div>
    </section>

    <section class="main-grid">
        <div class="panel column-panel assigned-panel mobile-categories-pane" data-pane="categories">
            <button class="mobile-add-category-trigger" type="button" onclick="showMobileAddCategories()">＋ Add Categories</button>
            <div class="section-title"><span>Categories on this Property</span><button class="small-button" type="button" onclick="toggleAllGroups()">&#8744; Expand All</button></div>
            <div class="search-box"><span>&#9906;</span><input id="assignedSearch" type="text" placeholder="Search assigned categories..." onkeyup="filterAssigned()"></div>
            <div class="pills" data-filter-group="assigned">
                <button class="pill active" onclick="setPill(this,'assigned')">All</button>
                <button class="pill" onclick="setPill(this,'assigned')">Existing</button>
                <button class="pill" onclick="setPill(this,'assigned')">New</button>
            </div>
            <div class="assigned-list" id="assignedList">
                <div class="category-group">
                    <button class="group-title" type="button" onclick="toggleGroup(this)"><span class="chevron">&#8250;</span> Architectural Style <span class="count">03</span></button>
                    <div class="group-items">
                        <div class="assigned-item"><button class="remove-tag" type="button" onclick="removeTag(this)">&#215;</button>Spanish <span class="grade"><span class="dot">●</span> A &#8964;</span></div>
                        <div class="assigned-item"><button class="remove-tag" type="button" onclick="removeTag(this)">&#215;</button>Mediterranean <span class="grade"><span class="dot">●</span> A &#8964;</span></div>
                        <div class="assigned-item"><button class="remove-tag" type="button" onclick="removeTag(this)">&#215;</button>Modern <span class="grade b"><span class="dot">●</span> B &#8964;</span></div>
                    </div>
                </div>
                <div class="category-group">
                    <button class="group-title" type="button" onclick="toggleGroup(this)"><span class="chevron">&#8250;</span> Pool, Spas &amp; water Feature <span class="count">03</span></button>
                    <div class="group-items">
                        <div class="assigned-item"><button class="remove-tag" type="button" onclick="removeTag(this)">&#215;</button>Kidney-Shaped pool <span class="grade"><span class="dot">●</span> A &#8964;</span></div>
                        <div class="assigned-item is-new"><button class="remove-tag" type="button" onclick="removeTag(this)">&#215;</button>Lap Pool <span class="new-label">New</span><span class="grade c"><span class="dot">●</span> C &#8964;</span></div>
                        <div class="assigned-item is-new"><button class="remove-tag" type="button" onclick="removeTag(this)">&#215;</button>Tropical <span class="new-label">New</span><span class="grade"><span class="dot">●</span> A &#8964;</span></div>
                    </div>
                </div>
                <div class="category-group">
                    <button class="group-title" type="button" onclick="toggleGroup(this)"><span class="chevron">&#8250;</span> Rooms &amp; Interior Spaces <span class="count">02</span></button>
                    <div class="group-items">
                        <div class="assigned-item"><button class="remove-tag" type="button" onclick="removeTag(this)">&#215;</button>Home Office <span class="grade"><span class="dot">●</span> A &#8964;</span></div>
                        <div class="assigned-item is-new"><button class="remove-tag" type="button" onclick="removeTag(this)">&#215;</button>Attic <span class="new-label">New</span><span class="grade"><span class="dot">●</span> A &#8964;</span></div>
                    </div>
                </div>
                <div class="category-group">
                    <button class="group-title" type="button" onclick="toggleGroup(this)"><span class="chevron">&#8250;</span> Kitchen Features <span class="count">02</span></button>
                    <div class="group-items" style="display:none"><div class="assigned-item">Kitchen Island</div><div class="assigned-item">Walk-in Pantry</div></div>
                </div>
                <div class="category-group">
                    <button class="group-title" type="button" onclick="toggleGroup(this)"><span class="chevron">&#8250;</span> Living Room Elements <span class="count">05</span></button>
                    <div class="group-items" style="display:none"><div class="assigned-item">Fireplace</div><div class="assigned-item">Open Plan</div></div>
                </div>
                <div class="category-group">
                    <button class="group-title" type="button" onclick="toggleGroup(this)"><span class="chevron">&#8250;</span> Interior Feature <span class="count">03</span></button>
                    <div class="group-items" style="display:none"><div class="assigned-item">High Ceilings</div></div>
                </div>
            </div>
            <div class="switch-bar"><span>Switch all to <select class="switch-select"><option>A</option><option>B</option><option>C</option></select></span><button class="switch-all" type="button" onclick="showToast('Grade selection applied to visible tags (prototype).')">Switch All</button></div>
        </div>

        <div class="panel column-panel add-category-panel mobile-add-category-pane" data-pane="add-categories">
            <div class="section-title"><span>Add Categories</span><button class="small-button" type="button" onclick="openModal('categoryModal')">＋ New Category</button></div>
            <div class="sub-heading">Search and select categories to add to this property.</div>
            <div class="search-box add-search"><span>&#9906;</span><input id="categorySearch" type="text" placeholder="Search categories or property attributes..." onkeyup="filterCategories()"></div>
            <div class="pills add-tabs" data-filter-group="categories">
                <button class="pill active" onclick="setPill(this,'categories')">All</button>
                <button class="pill" onclick="setPill(this,'categories')">Categories</button>
                <button class="pill" onclick="setPill(this,'categories')">Property Attributes</button>
            </div>
            
<div class="category-browser">
    <!-- Left: Categories -->
    <div class="category-list">
        <div class="browser-heading">
            All categories <small>533</small>
        </div>

        <div class="category-items">
            <div class="category-choice">
                <span>Property Type</span>
                <span>46</span>
            </div>

            <div class="category-choice selected"
                 onclick="selectCategory(this,'Architectural Style')">
                <span>Architectural Style</span>
                <span>46</span>
            </div>

            <div class="category-choice">
                <span>Exterior Structures</span>
                <span>46</span>
            </div>

            <div class="category-choice">
                <span>Interior Features</span>
                <span>23</span>
            </div>

            <div class="category-choice">
                <span>Rooms</span>
                <span>77</span>
            </div>

            <div class="category-choice">
                <span>Kitchen Features</span>
                <span>46</span>
            </div>

            <div class="category-choice">
                <span>Bathroom Features</span>
                <span>46</span>
            </div>

            <div class="category-choice">
                <span>Pools, spas &amp; water</span>
                <span>46</span>
            </div>

            <div class="category-choice">
                <span>Aesthetic &amp; Mood</span>
                <span>46</span>
            </div>

            <div class="category-choice">
                <span>Landscaping</span>
                <span>46</span>
            </div>

            <div class="category-choice">
                <span>System &amp; Utilities</span>
                <span>46</span>
            </div>

            <div class="category-choice">
                <span>Site Features</span>
                <span>46</span>
            </div>

            <div class="category-choice">
                <span>Location &amp; Settings</span>
                <span>46</span>
            </div>

            <div class="category-choice">
                <span>Named Architect</span>
                <span>46</span>
            </div>

            <div class="category-choice">
                <span>Period Lock</span>
                <span>46</span>
            </div>

            <div class="category-choice">
                <span>Geography</span>
                <span>46</span>
            </div>
        </div>
    </div>

    <!-- Right: Subcategories -->
    <div class="subcategory-list">
        <!-- <button type="button"
                class="mobile-category-back"
                onclick="backToCategoryList()">
            &#8592; All categories
        </button> -->

        <div class="subcategory-head">
            <span id="subcategoryTitle">Architectural Style</span>
            <small>Sort : A &#8594; Z &#8744;</small>
        </div>

        <div class="subcategory-desc">
            Select all subcategories that apply.
        </div>

        <div class="subcategory-items">
            <label class="subcategory checked">
                <input type="checkbox" checked> Spanish
            </label>
            <label class="subcategory">
                <input type="checkbox"> Mediterranean
            </label>
            <label class="subcategory">
                <input type="checkbox"> Modern
            </label>
            <label class="subcategory checked">
                <input type="checkbox" checked> Bohemian
            </label>
            <label class="subcategory">
                <input type="checkbox"> Industrial
            </label>
            <label class="subcategory">
                <input type="checkbox"> Rustic
            </label>
            <label class="subcategory">
                <input type="checkbox"> Minimalist
            </label>
            <label class="subcategory">
                <input type="checkbox"> Scandinavian
            </label>
            <label class="subcategory">
                <input type="checkbox"> Art Deco
            </label>
            <label class="subcategory">
                <input type="checkbox"> Vintage
            </label>
            <label class="subcategory">
                <input type="checkbox"> Contemporary
            </label>
            <label class="subcategory">
                <input type="checkbox"> Transitional
            </label>
            <label class="subcategory">
                <input type="checkbox"> Farmhouse
            </label>
            <label class="subcategory">
                <input type="checkbox"> Eclectic
            </label>
            <label class="subcategory">
                <input type="checkbox"> Cottage
            </label>
            <label class="subcategory">
                <input type="checkbox"> Coastal
            </label>
        </div>
    </div>
</div>

        </div>

        <div class="panel column-panel gallery-panel mobile-photos-pane" data-pane="photos">
            <div class="gallery-header">
                <div class="gallery-title">Photo Gallery <span class="photo-count">108</span></div>
                <div class="collections">
                    <label class="collection">CB1<input type="checkbox" checked></label>
                    <label class="collection">CB2<input type="checkbox"></label>
                    <label class="collection">CB3<input type="checkbox"></label>
                    <label class="collection">CB4<input type="checkbox"></label>
                    <label class="collection">CB5<input type="checkbox"></label>
                    <label class="collection">CB6<input type="checkbox" checked></label>
                    <label class="collection">CB7<input type="checkbox"></label>
                    <label class="collection">CB8<input type="checkbox"></label>
                    <label class="collection">CB9<input type="checkbox"></label>
                    <label class="collection">CB10<input type="checkbox"></label>
                </div>
            </div>
            <div class="gallery-content">
                <div class="photo-grid" id="photoGrid">
                    <div class="photo selected"><img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBAUEBAYFBQUGBgYHCQ4JCQgICRINDQoOFRIWFhUSFBQXGiEcFxgfGRQUHScdHyIjJSUlFhwpLCgkKyEkJST/2wBDAQYGBgkICREJCREkGBQYJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCT/wAARCACbAPYDASIAAhEBAxEB/8QAHQAAAQQDAQEAAAAAAAAAAAAABQMEBgcAAggBCf/EAE0QAAIBAgQDBAYHBgIHBQkAAAECAwQRAAUSIQYxQRMiUWEHFBVxgZEyQlWSobHTCCMkUsHRcvAWM0RisuHxF1NjgqIlNENUc3STo7P/xAAaAQACAwEBAAAAAAAAAAAAAAADBAECBQAG/8QALREAAgIBAwIFBAICAwAAAAAAAQIAEQMEEiExQRMVIlGhBTJSYmFxFCOB8PH/2gAMAwEAAhEDEQA/AOllzirdQy5DmZDC4Oun/Vx77WrPsDM/v0/6uOJv2nbv6X68gm8dJS2sf/BQ4rmg1VBeFlLHQXUgjZgDYG/I2ufhilGcWrtPo/7WrPsDM/v0/wCrjPa1Z9gZn9+n/Vx88JP4aoSKZTTRhez7RhurGwILD3n5YLZFQrV1EkcERnlBUCRYw+g33Yg37pUGx26eeL4kLuEBlGybRuqd8+1qz7AzP79P+rjPa1Z9gZn9+n/VxyVIFLHTYC+wwmVxqeVH8/iIeZD8fmdc+1qz7AzP79P+rjPa1Z9gZn9+n/VxyHpxlsT5Ufz+J3mX6fM689rVn2Bmf36f9XGe1qz7AzP79P8Aq45CK48K4nyk/n8TvMv0+Z197WrPsDM/v0/6uM9rVn2Bmf36f9XHH5XGpXHeUn8/id5l+nzOwva1Z9gZn9+n/Vxntas+wMz+/T/q44804zTifKD+fxO8y/T5nYftas+wMz+/T/q4z2tWfYGZ/fp/1cceacYV2x3lB/P4keZ/p8zsP2tWfYGZ/fp/1cZ7WrPsDM/v0/6uOOdOMKY7yg/n8TvM/wBPmdje1qz7AzP79P8Aq4z2tWfYGZ/fp/1ccc6cehL+GO8oP5/E7zP9Pmdi+1qz7AzP79P+rjPa1Z9gZn9+n/Vxy1w7wTnPEsgFDRO0Y+lM/djUeJOJVRZVwtw5VLTwxS8Y57fajoh/DRMOjtve2E8+lx4uPEs/wI1h1D5OdlD+5fftas+wMz+/T/q4z2tWfYGZ/fp/1cc28YcQcSrmFE1dU0UASMyx0VAB2NOpJW3UOdiDzB5HEAzHN81rswaXOK8zSKQF1XdpV6WB+ihHQf0wmVrvGb9hO0fa1Z9gZn9+n/Vxntas+wMz+/T/AKuOXeE6xapEp5FOuEAMJObJbuk+NuR+HjiSrS9nUzsGFnaxXoMOpogwsP8AEWbUkGisv32tWfYGZ/fp/wBXGe1qz7AzP79P+rjniARrNMGB1rYC++2GM0aIs2vvCW6k28RywUfTSej/ABBHXgdV+Z0r7WrPsDM/v0/6uM9rVn2Bmf36f9XHCUAZaho259koI/3gSDjeRBpG1+8p394wicRBq434g61O6va1Z9gZn9+n/VxmKD/ZaFuIM8/+0j/48ZgZUjvLhrF1Km/aWMH/AGw5gs0mgGmpL7b/AOoW3+b9cV7lsesmRpUPajQ4R7a1BFwRud+e3OxxYf7TlGtR6XKtlYgmnpQ4HW0MfLz3xXmXCSgnZbhO0uNhysDpNz0xQsBBPCVQ5nK1BDAE7AgsWv478x/m+D/B1E71UktOxiWEhGEiBhaxIK+8k7DpvfAzIIY80zeCGQxujPZVkBNrG+3O17jFg09F6m8kknYmZ7KWjUqNI5Dc+eNH6bgL/wCwxTU5KXbFG33tbGmFCMeacb0yyInbHhXCunHoXFxIiIXHhXC+jGFfLEyI3KY1K4cFd8asuJqVuIFMZpwrpxmnEzriegDfGGNSOobG+nC0MCubMdvEdMRxJHMZ6CTa2+M0YP5XwpX5xUCChheZiL3tYAeJ8sHqPJuH8mqOylMnEWaR86OhIMUR/wDElPcX4XOFcutxY+9mMYtJkftUjvD/AAlmnEUyx0NK8ni5FlA8SfDEtpsp4Y4amEcwbiXNU/2Skt2EbdA78vzwYgyrPOJOzpaypjoaFzYZZlg7OO3g7kgudtzy58+WGKVjUMbU2V0lNlVNG7R9tJZnJBINreY54yNRrcr8XQmpg0uPGL6mL11PnXEk9LQ55M9LRTlgmVZf+6hVVGr97J9K2w+qbk2254PUOTUOV0fqlNBFDASAYkTQje8bl/8AzE/DDvKSV4byzU7yOaio1SOO851czt4Y9vpBbe4OEwfaMysPTCWizXJ6lAW/hZI+8NiA4OnyHe+GK9qY0qonqaRnSWMENtd9HOw+e3xxZXpfjD0+UTgWs80Z68wp/pis1d6eUSx9NiPEeGClLWU3Uai2RZo2V5hHLEgJU3aFXLOR1DHkLjp428MWUamI0odSuiXv6x1vyxVldGkemohN6aY7rvZW81G7Ek4k3D+a+sUXqMp3jNwNgfMC17eOGNE9NsaA1S0u8SRVswVQGJDtYluWwwNqn1EWcm9r+Ax5LK0huSbkW33xoEuLWGN7Hj2zCyZd0gWbxin4hqUUWWR3I8rhXH5nDdhqXbkCPzGC/FtOI83p5du8i3sOt2S/yI+WBjW0N4XB/EY89qV25SJu6dt2NT/Euj9l2w4hzwA/7JH/AMeMx7+y6oHEGeEf/Kp/x4zCjdY0vSVf+0hTdt6Wa+VCvaR01KQum+odihv/AMsV5EsjCOSUNGSWayLZWW/gN+Zb54sn9omZx6V8ygV3idqOmeNlAsSIU5+fP8MVz2uqePUxRhHd3Fu7vbbf3HCjAk1BP1kp4ZyaLM0hlIqVjBVyyagAFvddtjcld+lrHnichljchrl/5gNj8P64a8K8PRZbk9OyGdJaoGVo4nLRXO4037osLbDx8sSwcP0cZBaasiA2LXBBHhcHHpNPtxYwpiGZWduIDjhp+1iFVE6qTZuzP5X3w9qOH42i7ShmaYMNQVgAwHniQUuXw0kalWaZV3UyPyONazMVVSHVwTt3FFj8sE8Y3xO8JQPVIzJkNRGgOk79Rv8Alf44bPlk6DdQf8JBPy54J1lXJJrGpyrdL8vPlthiamf/ALwm382/9MNKWijlB0jcUzAW0nVzsdsJMmk4eGolbmVPlbCLXbmeWCi4I/xG5TyxqUxvVTR0kDTSkhFG9ueBsGfUeYxsaeRgoYgsyldrH+1/jij50U7CeZZMTNyBHui+DPDvCGa8Tzdnl9O2jkZ5AREp82/tc+WGGS5ZWZtogpIZJpNRjuRzIOLag4uy7gPh2hoa2oRaqlis6p32vcm4UdN9ybfHC2q1nhr6esY0+kLsd3SVfQcL5nX1LQwU+sJu8n1FHiSbAcutsGcvynK6YhKdH4grLgMKV+zo4j/v1B2v5ICdufLGUeeNxjmdJl5yYR5LC2tomZuyc6TpZlFg3QWYeOJtFFHAiRxoqqgsqqoVUHgoGyjyAAxnPrMuUUTUfTS48ZscwQmRVuYU5p8xqkioibtl9ADBTn/ER35ve5APng1RUVJl8UcNNBFEkZ7iRqFVT4gAAA+fM+ONtV+e/ljA3LkN8A6Q0I5Q59rUnIAygfPEKkySqrqqrqlqHSGOqnRUguGQLKwLaua8ibjTiTJJNEwkp5BHMm8bsobS3Q2PPCKZfDUPqrmeucEtpnt2QYm5YQqBHe5O5W+/PAnUnpLqa4nvD9VQ0uU0mV0wMrpUzSloFLxKGXm8gNtRI8Sd8P3BZZPC+NI306VvpABAAN7Y8fU3aADEVQlpB/SrFryGikG+iqAv70b+2KtK3xb/AKTaVhwmXI3SojYC+9txy+OKiNjhlPtgX+6OsrpoJ6auinIAdVtc+TE/gOmGmRqpDvHURyPGRpVLaUBvbYbe/DvL07Wfs9hqZevky/Pv4DcIwgZjFGQBqDDfkTY/2xQpzuHWSHsbT0k8p5I6qFZo9gb3XqCOY+f9MKBLEbYA5TmSUmcz0DyfupGstz9F/A/kcSUptje0ufxcYM8/qsPh5Csi3GtOop6WoA3DmK/w1j8UOI5K8cYJdljB5aja+JzxLSCpyKpuLmLRKviCGF//AElsQmOnUEy2UsVIJe7H4HoPdjL+orWXd7iav083ilw/suzCXiPPdIbSKSPfSQPp+JxmHX7M66c9zvf/AGZNv/PjMZjdZojpKp/aIpnf0uZhKO9aCjITSSCOxUG/TzxDeH6Serq6emp6WGSTYXdtpFt1/wCW/wAcWD6fSV9KuYoQseqmpmDiSzMBAlwQN7bW+OK6oGegl1RRu6vH2bdmSLjoxN7C21reJvhctXIlK55l3ZTP21FC1KPVmjiEYjQtqBFgdyTqt/UYe0OXsZUlFYkM3euwF2a/wt/1xXtHXmjSCahqpZCe0ZyIyAu4LX2I36HcnFgK2V1VOlQaxKdncoYpxYowuTcjkNj3tht7saWk+oY8q052sOtxXNgZTa8xU0nYKS1TJI4PeKAix8AT8PLGkzUzdoC1UrLsASh3+GI1W8Uer5qlBTUbVAaVdMiMSkkNu8ykA8j4XPiMS9qCjDMsEpMgAJXXuCRcH4jfD2HPjyfaYB1cDpBr09Ewv2kwbqG6/EYbPT024GvY7dcE1y19WySL7rm/ntheLKyzXcm/gynDe8KOsXKMxgCSnj30hx4XGAq5xTPmTUGmQEXtMbFCRzFwdrW64sP2c4XSig2HMEqRiHcQ5LQLmjevGppqWWCUP2YjImduYANrNcjveJ63wHNqnVdydoTHpVJpu8a1tHDXQ+qzMRHMdmQ8rWN78hgHR0NHkaSNmSSvK8l0RGv1tYjmu2+JLTwZrxHlsOS8P5cuU5ZEwRXkmDSyEfzv0G/kMMIMho8vgM1QTXVD91uzYMCxUkMb7W28xjM1OqbMQ1VNPS6ZcQIu4jluYcQ5rP2WSrJRxF2AdCQw1WGxt5geJ63xYvAHosos7p4szzKuacNaREAN7Eb2vy5chyxAEzXMIarL8uoZ0pYZJYO17NBqa7c9RFuYvzxePovUxU3YFAsUUPZoxcXPea/LmenlpwspNXDsIHzaCDh+rho6Knh0S5k9IzyAl1RYgwI6A3vva++Fg97c+WNeMO1GcxdkTpGcSF7LfbsU8tvwxqWWMAu6RjxdgB8zgyHjmAaLBrX36YwNba2NYRLU3FLTVFSfFU0J99rL8r4WahqAoapqaOj8QP3rfM2X88cWEjaZihmBsCbeAwmcwpoXF5lduWiMGR/urc42EWWtcyetZiw/70kR/cGlPkDhKo4mosqiKtPQ0Cj6obvfdXvYqWuEC1HMS5hUENDQPF4PWSCIfdF2+dsZNls1Q8UlVmgiZGF1o07MEfWGtrtuPDqBiNV3pFywbJNV1b+Cp2Kfjb8jgK/H+aV4JymhjUatJZIzOfnsuBs4lwhkm41TLst4WzCamoyZD2avUSFpJCDIt+8x1Hn+eKmqqGOVDUUbKy8yoN/lg5mua5vWo65vmEUcJN9FVVpGn/4k/tgFLmeU09//AGyL3uwoaNjc/wCKQgH5HFly0OBIbGO5nuQqGzJFIuLXIIvazKcAsmUw5uYrsrBnj7vTmMFoc0yevkdPZWYV7hdV6uuaND02WIKPxwxliMtZJW00Zgl1NI8AYtp5m6kkkjy5jBky+8C+PupmtdQVNVnkooIXknaUuyr9IXAbVc9N+fjiU51mlblGQwVUghSsOlZEfvgmxvy+d8MeH7R8VF5XAWSk3lYWFyqnn05HDvixMrzigslfB28Lhkjd20vYkEFQRz8cOYlK4nZDyYjnO/KqsOBIZnGc19QJKlGUGc6NNihOoEX352udvPDmJ2Eans20ldV/C+/M2A54K5V6Nq/PWjzAVtItJrLxIrMWjYG1rd3p0N+eDsPojvBpr84zGVQLfu4+zA3HIkEbWtcYyQ+Rid3MfUKo9Ikz/Zol18Q55fmaZG+kG+v5bfjjMG/QVwdlvDOeZnLQLUFpaZUeSaftC1mFuth16DGY7nvDf1Kd/aFhVPSfVzwhPWHgp0YudtAgTr9U8yD5Xt41/NV08tXTtFKACLSbnRIeqhed7NbFo+npVo+Ps1q2Erh6SntGp3LdigBH488VHRV8FfGtPVCZjG4bUzBQBsOZ93XCLMbPtKvQ5jrLqiKQESuSqq0i6luB7weY6WOCFTxLXz1om9ZZpEYgK6qBv1C9By25DCUjULRyRVVOxenUxB1Ox73MkfS5XwwpElSVZ+zL00lgDINRAJuBfn05dcLvjV+albNSS5RniUZjU0lHTtJGTq7IyFje5Zrk2PkBiW8KV1XPmSZvmIcxilZ5mbSoDWCLpUbgG4G17dfEVtVDXKY5HICv9IFRe99wfh+GCVbNXU0NEUmjqqQ3AKNshtupP81rG+DaMvjYUeJ3DdZbPBWax1ldmUorK2eN2Dokg1IoJ5q30TuSLL/LfErmrlC3VJLjbEI4A4aH+jFCalmqaQVEc8UaL/qZFOk7qbkctrDBvhetizDJ56uaUIBXVas73VQBMxXc7AaSvXHo8bLQuJ5Aw6R21aalGMTudLlCfBgbEfPEf4hoGzKneNlV5Gjk0X/mtcfl+OH/AAnUJXZPJUGSNgKyr5OL27Zm/I4a03FdHWV+T00MEnbVrIy3TkCvS43vqIHiRgj5cQx03eCRcm+17SNNl9Svo/oKuHtFVezE1xtcMALA7X7xuLdBh3RMk3qUz2UT04LhSLglLDYAC17/ADGJbl9qyhny6elSWhklZnaSFkCyd1wbEAbbbHzwOr8qNDQVUDTUka0wUKUkD60DA7W2I7tjubfHbJcgc9prrdcyIrlLGWidp4ou0qIVEjE3FmYEAf8AXfni6PRVVRxJSwnRoaJ0htJqNy12v1PI7nFEPxIElaN6T1sK7aSLrrFydXdbuncYk/C/HWZ5DS1lfQulNqGhkjpTOEIa9wWYAA6twLnrtgAzrwBLFe8tHNKky8S5nSiKWSGOpMspjXQxBQbBjb+W5074ZVnEeU5BIo7KkoZHUspdGllcA2Jvuefnisn4gzDP60zJlmcZhUyG3cZo1ZmO5IUdT5j5YbVmZV+WzPS1UmSZLJESjwsyNKpHQjvvff44OGI6iCoHpJxW+k2KaQx06VtUzGygDSD925+eBFfxjm4DaEy/LfB5pFL/APqJ/LA7LeD67irLIMzXiQSU8zyx2EcgsUKA7MR/N4YI0nopyuAh6mtrKgjmEVYwfnc4sATzcgsB2kfzDiNql75hn5qQeaQo7AeW+hfwwNOZ0eotDT5jWkC/72Xs0A8xGoNv/McEPSPlUHDHE09DlNOlNTBAygjWw7zD6TX8Bh76IMyqa7MOI6OqZ2/gVEeoAhgJkufAc+VsCJXdUIA1XBDZ7nKD+GhocrTxWNAR8X1H5YeZZw1xBxjQVtbHnEM0NG8UcgeZhdnawA2t/TFewPmU6NBSUr1UzKjq9NTs7RsD3kI3uLdbc7b4uT0N0ucUXC3Fkeb0dfTMyUs8RqqdodYVmvpDAX6cvEYlXO6qqQy0LuV1meUx5HWS02Yo4eNinai5icg22IH52wPrquKkp5JIRTxBRcS6O0/qcTvjApSZxmHcAXtWJN7AklbDlb6wxX2fZdAIFq4YjBI3f/dkrYbXuBsfpDBSDugwRUmdXSUkHAmWZvTw6aybMZ6aol1G8ihAyCxNhbfkBfA5HizFC8bGOZV1qeV+nPpjIohP6F51YEmlz8HnuNdOcIVYtRzsqp3UUjUt1HeHT44lU3AXOL0OIrJTwSSCSqraSOS3f0yX1H+a19j7vlheFMnjI/ipZj4RRk/mMD6anzOQApC6g7i0ar/TBQ5XmlXSxQGCZCjMxkSYqz36NYcsM04EXtT1kh4Z4qh4e7ZFyiqraeUaikpdQHHJtmA5c8eZnxxQVNQ9SeGslSVrd6qn12tYCyXIHIchglwzwVR8TLNUV9LM1fGw1xpMkcWnbSbOb9LHEvnyM5VRetwZfQ9msyQt2MsR7O/U6b8hvbrtgDAHqYRTQ4Efeg/izMuI81zKOsak7GKBGRaeNwAdXi3P4YzEg9GkheurBquOyXb44zAmUA0IVDYnOH7RubhPSlLQKyRIIqV5mkNgymJMVjQQdk9V6s6OEJiKrvZiTa9+nPcYsP8AaEiib0zV3riMaRoaLtDGB2gHYr9G+x93liI5fSZHmeZ3pKvMFqWAYRT5elpDbYbS+/4thRhOKFoPTMpYk1uheN0vcKFC2OwJ8BbDiOeKaiYMzFACQrd0WAAFjysPHzONDl+WPAEjzYKJGdlV6aUBWXmARe43Pyw9ouHxV1IKcW5LHEe92k7uDpv4FRf8MV8LdVSNhHWMqKnapqXktNOsIUqqRkgoR9IkXAsb+AO++DmULUK1XXKaQpR9nIkDNq1jUELAdLX3/PEu4c4N4Uy1Vnq+Ioq+QypIZDWJToHQMo0hW1cmbrviU1eaZAs2XU6VOUNTyVJ9YDTxsqxmGTdrk7ago95GCnTCruSBZ4kc4F4sTKDV0GZuwpnp5HiQ95RKTcbdNxz88SLgXO8uo+BpY6+7zy1U/wC6SMt9IrbyAB8fDDTNeFOCKtBLHmVLlTnfVTVKyR8t+4Ta3+EjAbMOH8pyLKqWvj4pNVQzTOkUlLRFlZ1+kLGQXtcDbFS2RRxz2l9lmSPgHMcvk4IraeOUetD12fTuuoC9iPgB8sDeDskzT27wzm0NN6xSJTxtNrYaWCKFfzGkXPvwJyzIcuosmjzWLO5/VZ3nSOL1bs2lZVGtbazzuPHrtg7wLUs+YZEfXA6TS6AisSQN+4bcugPTbAsmemUMPYCXRCASJMIaeXiOjnrauhipWdmYS0QZljJRRpNz9HYG58eYxC+KeDOJBE9fWvl9XT9kvZzxSOzABvAsQtwDckE88TjJM3mpfR9LJSGPtAJxIkiBrhad3HPoSo38jgZTQVWaVdRlKV0MaswWVJEuOx7KJwxAtfvM/M/hguRlPBlgO8qVBHTVkrQv6urRugJFindG7WsDv1tzvhTIK/VnFHHGEWAVESgKbXYuLg7+XK3nh7nUU1LmdUsp9ap4zNDHJCwAdibDncabHcD4eOBNKxTialqRGrJ6xBG0QQt2Q2sx8BcHwvjOQgsDKM9Cp0bErGeIb6RKu19vpD3YqH0l5VkK8ZZxVV8ubA+suHWngjCgi4+mz+AP1cXbDTd4Eg2D72B6HEdzrhKDPq2prK7hPL6iSpkMkjS5vMoJ3+qkVh9I42MylloSmJtpgj0bNl8nCNLFlqViQR1FTb1uVHdiexN+4qgDyt8TiUTQ/uzsOXjjXIshmylBBBleV5dRgORHSSzSsztp3JcAWsuCrUjspHe8Nh0wTHaqAZXJyxIlZelrtIeMCsNDl9RI6qqNPAGYDs0e12bTzduYwG4Jqczhz2viqBSU61OV1gQUqwIdSorAnsxcEWuLnFh8UwLX5xDPmvD2TytKzJDLPmUy20RjcqsQsSqja53wpTZBVZdN2tHknCtJIFZBJ/ESsoYaWFyy8xgbIbuX3iqnPPE+c8RLm1VSpnmarCk0qCP1p1Fg5FuduW2Jz6B45JF4ujmlaWWXJZHu76m7pJ679cWYMiqQxcUvCEUhN2kGSGVifG7z88K+oZ6IJ4Yc+o6RJ42if1PJ6eK6kWIvcnrghF9IPvK949oGqGzZowWl7VWVU3JBiif81xCM8prZYuxI0yWJ96H+hxaPGdBV0uYpmCTr6nNNDSSxjcs/ZE3O1rWAGxvivc6gr1yRESOVpjO8cipCXBT6pG3z/rg4omzBkGK8PQNU+iriaEjaHM6GXl/Mrr/TDJwJMsl2+nSqfwU4lfo4ySor+GuJsplinpxVyZd+8eBrIBK4J3AvYHA2DhbMhkOZVBglPs8eqTIIn1DuN+8tb6Pc5+YxyEA1OKmrgXOc3qsu4ayyrpZWQuiRMBy/1RP5rhPgvOZ86zFYq2racMjnsWPIgrvtz2LfPHmbRLUcEURue5UKNu9yEifLfDbgunio89poQ4aoTXGwCEKCYi/M+Y8MTl+9alV+2N0zOpjzIJLUF+ych42ubqHdbm1uqj546K4eNPN6HqGelhWJe6zFUCl2WoMZY25kgDfHOmbsaHPKhWgUl5Zl0gXGrtGbmRf621sdCcF1Cx+hyZZozGsFbUwd+66ddYShAtvu64WKkZIQHtJV6Lzeurf/AKS/njMe+jFdNfXAix7JfzxmJyfdLY/tkS9IXDeT5lxRNU1mVUVTM0MQMksKsxHZAWufInAODhHIYXDpkmWhgdj6stxuD4eWJnxiB7ee4/8AhRf/AM1wKQAOB5/1wMCXgP8A0SyJrk5Jlhvcn+GXrsenXC8PDOSRHUmTZarnmRSoL8/LzwXjXVFG1jcpqO3ux4xvEZVVrWDAHYnbF6E6Vb6RKHLpeIsvyxqWKKnlhXUIUWMqTIe8u25+GEKj0dZMuY01N2tY/rxeGUki6oimQWNtrlRhz6TIgeI6IxGOGqaBEVmJvclj0B25XwnXVyUzZcz16r6nKZns1i90K2u1rXvhPb6jC4jVwsPRnkrzwVCmummItYMulV5Aadrnp8L4H+kfKaTJuD6fL6NJVpsurZGBZgXAeNpH5kX7wFvC/XAWl47pIs6WtrKupSm9XMKreOS1zdRs4Ow2wYzDNeGs44fehmmnkpmnE8aB4UZGWQna7EgG5HuxfwwBzJJvgCF/R3w9l/EPAmURVryuIJqliI5ShjYyt4f7tsTXK+EclyCSnqKOGRJKclo2aQtub7kHnzOIRw9MMi4dOV5bJFBqMjLM0jdwsbhrgG/4YO8PcRRUlNDFxC0uZmNQP4J1/eebFnB8OQNxgbtjrcev9H/yXAccVDWUZPSZlT5plsGimgpU0K8cbsTrikGk6Qb2L39xI3wkcqSmzOafLMyo6iSWIRhGVh3tKpZrjYHT1xID6Tsop6FIqSCoo0RdKROmyjlzAPzv8MV5UZ/mMmftXZZm8SQEXMbA6lk17jdTzXqT8MByZUNE9pdMTNI1X5LUT5hLlMapE9RVW2Fl1WBJ5bja4AA223wFEVVTVdPKe0pDTzLCkIUMCo5tewIF7WuPrjFhZ+1LP6lVUIMdaYz6xUSyojI99ijMRyAXla9hviNE1WYuZ6xe0rEqO1NXKySGVb9CL3awHla/lhLjf6feWfARyZfELQzSMscyu4kKEX5Hww3oauPMKmup0Y9tRTmBwb7kXsfkMVg/EkyVtVUQhh284kZeV7KQLW5WJvghw3mM9HnlJUq0s09RKyzhiQH7Tkx/3gPzOPSHEQLmWr2alleq2a5O/jY4F5vmlHQrLTrUw+vdjJIkRHeAVSb+7a3xwjBxBBHx/XZbUSNGBGIITrvGWsrG/nfViOcSZpQjix5aiOQVtMxo5IzMTGYHWRWYC2x+gb+WKKDcIeBI5UcRT5nRtUT9k7rUG6WIBVoyCfvbj3Ym3AuaHOct7KrkWSpgKhiR9IMSV/C2KysY5Z6UM2uNgrDpsLg+fPEl9HM/q/EqxzF2haJ3ZbfyC4P4YYyINvEWxsd3Ml+b59S5VWR0ciqs7Pp0m52PLptfBGSmi9YMHaRCQLqK2N7YqfiDNHrs8mro3kN9NiwvfTy+GHlfxbO9YMyiETyywgSxlTY8+Xh/zxQ4yBCeILlmVWuhyacpM8NqhGBjdkveNgPDqB8sA8yzVqSTtBX1bJHUR6l9Yf6Oo369QfwwE4g4zoYMvhhmd3geCnkVYYyWEqM4Yb2HUcziMpxfkc0bwSZlLEJe65lUajtYdbdTimwwocSxMumFdwvxFDPUzzn2ZTMBNKzd4GTVa5Nie78sCvR3RzT5fMy1JM0rKA8rudKpcLsCDsAwBBFtvdgBkPE+SJFWJHnUJM8RR1lUIujUpO5JsdjYeeC3o/4gp45pqenDSkRmWGCbQgl3fYMD9I6hYb/0xU2oNyw5MU47yDLKujr6apRYKhGeQXGl3ZZFudYsCLk7Mpv/ADcziJp6NzltctfQVor5FZQI2jWN2ZIihAJbS5tvdT8Bid8W1tNmctTAimGaOVkkiqLKylln+iT9LdRbrYEm1rYToMzNHkIkUagzrBIdP1ljIa4I3s6sLHruMWDEcicyC6kGzzgjLafPKCTOszMAzGokc9jB2giIfQQGJs3IHpzt0xYE82R0vo74hyPK8zmqZeykrtcsXZlQNBNh4Ar+OI56Rsjqc4oqZ6USSGlrqwBpJDqItE4IOlvHrb34jOUcVZlKtdBW01NHFJST0M0gUXBZdLcn2YbHFGyLwWMhcTdukvP0PVy5rSnMFNzNTrqHg4NmHzB/DGYj/wCz1OkMuZZdDK00YjWbUfqkmxHx/pjMV3h/UJwQp6TNeO8/oKbimeklqoRKkUN49XeF41PL44FwcQ0LNqDyabkf6pjvf3Y29JGUxVvGdQ8lTVpenpzpjk0r9Af2wE/0ey229O1QeQ7aZm/5YHuaGASGxxLRIqMWfTaw7triy8r2xHOIePYsty9YEVVnnBSMyAMOg30k25+/Clfw3TtT66KkhgnTvdnEm0g8OW58MVvnWV0aIy0lNFDOH1QGPQmpyLkPfew393UYGWcQyY8bDrDXEVPTcRZhT5jVZ96uyRAOkEDMzEG5J5AbbW8hgcDwpTpofN6+oW2ghI9mF77g3H4YBTrV0b0kFbNGJ1GsoWUISdtwOdtxzwbNBBlzwTSUcVRHpLGQRqI2JUWufIgnfxxXGUyGj1lMqZMKbivE1Oa8IQ20ZbWVFtru6qL+PIYVTi7L0hJo8k0RqbF2qGAud7bY1jzGKpq0kjyiieWJSA7RBrD/ACB4Dn44mUWVLxRwzltRMI4SRUzSJHZAzBmRAAf90D34bGnTpUR/zWIJWBsqj4izilhrsqyTKooKnUY5XYXazFDzN+akcsGY+GOMJLCbO8roS31IYS7H3bDDzKMtnTganpbTx1ccNQw71tP71mA2PUEn3404Sy2nTN8oqRI3aACVVbc/QYksTy62xBxV0En/ACjYBiVPwtnsUUtUOMZHAQsvbQBo3VUZ2IAuRbSRhlVUXFSWY02S5wmrTdRpY/uw/JrfVYYnGX0CxcHzSwR9pITKJCAdk9WkFtvMn4nDPLqQZjndXS1Md4TEtzc/TNMm4HRgBe/PfAn60RCDMalfJxgmXzGCs4dkhkU6SIADY+QI/I4enjHh3MowlRNUwCx/1kZNr7E90m2EM3ppqLOKhqermjNNJaOZeZAWwIJ33HPEWraRyHlnCSFiWXs1sbWJO/y+WCLiB5qLn6gQaMm8JymtH8JxDTX5hZdP9bYftkudXhlo6ynYQusqdnI0eoryvbUCMQyv4WSCrmghjkDRsAQj+P8ATDODI5I3jkpa2WlDuF1LdbNtzK2+eDHG9dZKanExrbJ3JFnkFYlW1BI0isJO1jmVzcdd7G+G+f19ZV8VZnmE1HVerzv+7laIi4ubEgeRwErTxhw/m7ZWM3eqnUqFUOXDgi4trF/xxtUcc8UZJUvSZhSRmWKwddJv77g254GPEBsGH34SPb/v/MdPm9HPWuzSxFgoViRptbxv7hh/wrMtFm9K9HUtUM1RdVMuo9/YqLnlucCo/SfFLtmGVh+V2WTUfk4H54dRcV8H1hXtKPsmO4LwDb7pOLHM9cicMKH7TcdPFNT1Usaho5V7WMbAlX0Mot8bfLFdUHE3E8NfQPXS1MkRlhEizwLupcahut+ROLFji4UqjanzFKdz0jqOzJ+Fx+WHo4dlMd6LiHMFQjl27Ov52xDZ2PaXGmqDc1qmhpw7IJBHKISBbbvab48ekR97IR4Ye1HC2a1NI8CV1K4cghpFbUSGv423643kybPodjRU0wv9SoK3+Fjgy6lKposdM/aC1y2nUsGpoCJEKnVGDYH4YB0WZSZZnrw1CU9RS04iCI67W0lTceNxfbEoNHXI958orI7HdkVXHzuMRiryeGepepqjmKOTcIACg+d8C1GRGXgwuDG6n1CEc59IFbVQ+rT00VYJneONJS7RxIoZCVcXa417cxY+eGA9JcmWF6SdKapo5LySU5nYdmzMzNoLID9YC/gLYFZkktNCHijeREnYpoQsSrAXBAG30PdiFZjmCSShZQQ42JfYnBdKiso5kZ3ZW6S4804jFVwNl2YUbzJ2WbiZpO8GWN0CGzi291AwwzGtbhOsjpa6qn7Culd5pomYTM1vpsSbWFhyF+uEuDq2jbhuPLc1GuIlZDFKO4x3I8iBf54mMeZZbU1IBXItQteaagjkkUDwYqfDkcZOrUnLa9BNLT5FXDtPWTb0BVslXmWaCpVTVJCBJICLkahbrvtvcbb4zDv0ST09XxlmctLPLPGKBQzyxhCGMpNgAT3fAYzB9Om1ADFs77nswP6RKr1bjNyxVValhG4veyj++AcmcKqHp53wR9L76OKIOfeiRf8A9aH+mIcWZztY+Y/6YYVbEXJ5j+r4lnjW8Q38SdsV3V1rycVyz1IiMkioyHR3RfUDa/U238cS56J2JYlt9gMC6/JTN0Gu2xH0l8xijCpdYnTZS1a5lpqmOnUED/3dHfe+wZv7bY2zPIp46RmavzCslJUCPtAqm7C91FgfjjMvafLoJmrUkjCuF7TQ2knexBAw4GdNNIzUyy6wp0uVtf3X/tiFGNf7hMjZsiELZEfcLcP8RQFjR0NBE8mxmqxrOm4PIXA5eGLB4aopYsup/abwVNSRvJ2IGgXO3w9wwF4ZzRZssbXMGqmbVMga4QncD5YI+0uzNtQ26jGkmGwGmGM1WIpw5QGDhyjp5pb6VkBBuTvK/X44WoqOEZtQU0ThAWMeocwAhA+V8NDmaaQosB77DAyrztqOtgmhUSyRMG077Anf8B/kYuykLO3qWEnOQzHhWgqKGZ1mlDhdKHUDdV+B58jhhmmZeuZbKfZQRpFJaeHTdCLAtbqdO3PlbAmlzQZplmuqijZSN0ivcWUXueZ5WGAuZZ1WTuaakgaGlVPoyAKdRI0m/Xbc+FsZ22zceOWl2rzInmta9RmlSWBgWSR30GwC+W3LlbAqomKxOFU20k943tfngzmVJDG4lrKkRF99TnTq+J54EPxLlFHMIaal9elIH7+TugeHeY+/p8cGDACIjSux3HiXQMpoW7xp0Z2CNIT9YrpIH4eWAdPwpTDPamqsEpIiskKAXFytmuOmF4LRIHj2UqGugAvccid9741biCKOreklnQVKuVMUylGY23tqsGBFuR8MQHjZwdI6myZBnqZoqQn92Vk1L3wwuAR0GxPLEP44yek9uaXlm9drygh1AlIxrUEFv8IPjicxV6OxBvHcEAOeZ8Bfpz3whXZZSZq0cs0V5IG7SOxsATboNjtcfHHKaMq+6qlLV9Netm7sTIh0LtcGxt8cIUWURVeYQ00g0ieZUvyCAny2xJKzKpYqqqWWLRoDMAwIuL9Pn8cKcO5TLV53CUV/3TdrtsO708sMsFqxEUyndRkSqcoCVjQxySxDtNNiQLC9twLDDF5KzLwJQZoNQJDxgodvEoQfni1OIqDJMuzuOuzigq5qOru6CkZVdCALhlbzv/TDvMaLhmshiNBRSBXQMHkiEUgv0up8uZvhV2QctNPBjyuf9ZleUWccXxzrTUtTVzSHkHZZBbxJa9h7yMWDk9bnFPTD2lWR1E559hHpVfIW+l77D3Y0pqaCLVFR0wDyHVZTcnzJJJt5nBGCmNPZ2IaTyGyjywkzbz6RxNcL4S1kNmEaeeeeO9Qx0/yW6eZAufdhVkjdbahbwYWw1jqZIVuY7k87HGHMYyv72OdT0IH9sGA4qJseYjVZTl8+rtKaFm/m7Nb/AD54GzcMZbI1+xYDxVjgjUZhCVOmSS55C39MNWqrqQoZyepQ7+/bHVO3GM34VoBGQs04Tw1BgPhho3DdNCxMNTHfwdCbfjgqtWzAIWs3moAHzwhNr0XYC5/zzGOCL7Tt5HeTr0EU4p88zNRJC/8ADKLxgj6/W+Mxv6DF057mt739WTY9O9jMWlBzAXpeAk4lHMFVhFx5xDEepaZpRY2GJP6Uo0l4mlV1DALSmx/wDAOm7o7u1sWBoSK5noypWsCtz78LxZfENhGoHiPHD2IcvMb4E5VVT1ldmInkZlg0hFGwAN+g2PLripNywhGaipZ4Xp54w8UgsyE88QfMcqfJqwxFi8T7xOR9IeB8x/zxPKUmTtNVtuVhbDHO6eKbJ6jtEDFI9ak/VYW3HzwHKgYXGdPmONqHSR/Js5fLyYZRrpmNyAN1Pjfr7sGXrYnRSiXB31DriKRMSoueYvh/l7MySIWOkKGAvyOGNHqmU7GnfUvpy5V8VDRhcuW22G/I7YYSRx0c/rddWxQXVltsu3Q2JuTv4YCcQ5xXU+dUlBDUNFTzbOEADEW/nHe/HGS08UTF1W73tqYlmPxO+NQkvxMEYlxmzzJAc1kpMnepoaZ5qWIABmcoHuRvY7n6Q3tgacyqMwy31k1CU5kQlEiNjf6OzG7E6iP8jG2Z1cy8JmMPZWVSRYb96PDaljVY6RgNwsDC+9jdv7DGY1kkCaKkBbkVXK5FnmM2qdWdkDyks3Pnc/H8cFqLgxq2C8EkDyyxyqyTRkaNjpG5tc9GJFt788FzGmgtpAJNztzu9v6nBfPmOX0mcV9MeyqIkLow5A2A+jy6npgZT3lQ1moejZ4qeMOSW7NbMDa5sL8uWIj6TK3O4ZqGSlpzU5etFCzrUU61EIYxqATqB0mwPeGk4S9Gme5lmqVEddVvUCONGQuAWBNuvPDv0i1VRlwy6spJ5YJ2yqAlkYjVtyI5EeXLBQsrv4g/gPNZs3kqEpZKnLhRxpNLT9sZ4JkaRUsoku0Vi6nZiOmJ5HNNHd1UEaQ11vsCbbDl+IwOyTL6T1GpzRaaKOrnypDI8ahAx7WE30iwvcX5YKU0SsWh7ypoLWViu4AYbjzxEIORzGNfB69Ts06MEmUKHIK6gCDzOx3t1xmSxZdk5knMzwTMbsSnvta1/HzwO41zOsi9G8dTHUOkwLPrXbfXFvgDQ11RXUtHLUOGaWBZHsoUaiBcgAWHwxR8xUQ+HRJla5Is5zaLMZk7OJeyisU7VQ1yN72IPyw0jjqK2VgDZQbvIwuE/ufLA55GXYMRd0X4FgD+eJN2SQzNBGoWNNQVR0scLKC5to/lZcI2IJ7TxR00ZSO41bsxNyx8yP8AphRIX+k4B8Abm+N6ZF0hrbkeONXJSSTTtYC1sMADtM4mzZm5DEamCBh78IvIwazgb8rY9MzsSC1wBfCUpJOsnvEc8TImkqoBcIu3UjVhrLDKQCNWn/EQcPERTEWI3w2lYgHfHVOjZdcQDSLIBuLhQ4t548EuiJY1eMrzF+6bflh1f92fM72w1mUdmp0rfxtvi1yhlkehBSudZoSGF6dOe/1vHGY09BBJzPNLkm0K/wDFjMQeslek/9k=" alt="Property photo 1"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,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" alt="Property photo 2"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,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" alt="Property photo 3"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,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" alt="Property photo 4"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,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" alt="Property photo 5"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,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" alt="Property photo 6"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,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" alt="Property photo 7"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,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" alt="Property photo 8"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,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" alt="Property photo 9"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,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" alt="Property photo 10"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBAUEBAYFBQUGBgYHCQ4JCQgICRINDQoOFRIWFhUSFBQXGiEcFxgfGRQUHScdHyIjJSUlFhwpLCgkKyEkJST/2wBDAQYGBgkICREJCREkGBQYJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCT/wAARCACbAPoDASIAAhEBAxEB/8QAHAAAAgIDAQEAAAAAAAAAAAAABQYDBAECBwAI/8QAUBAAAgECBQEFBQMHCAYIBwEAAQIDBBEABRIhMUEGEyJRYQcUcYGRI6GxFTJCwdHS8AgXJFJVk+HxFkVigpSiMzRDU2Ryg7IYJUZWc3SEkv/EABsBAAIDAQEBAAAAAAAAAAAAAAIDAAEEBQYH/8QANBEAAQQBAwIEBAQFBQAAAAAAAQACAxEhBBIxQVEFEyKRFGGx0TJxgaEjQlJT8AZyosHx/9oADAMBAAIRAxEAPwBm9raiTtpmA1EFZYGuOn2Sj9eA1JpQC5GCHtckc9uc6Tu7oBCbgm5+xjPHxwFopxoVgCAR12OLQhG42W48OPfbCbiJYrepJ/C334hhmBPricOW3vbAolJ1sSTitmFBDmMDQSi9/wA0jlD0OLGoILsRt1OMGqjGyDVf6H54hF8qwayEi1NPLQVLU848S8HoR5j0xJS1xg2vdDyPL1GGTOsvXNKe50LKn/RkDr5H0wmv3kUjRuCjobEHkHGc7onbmldSN7NSzZILROTOXVjHFSPKRxIxCKfxJ+mKk2YV81xJUd0DysI07ep5+hGI45Dvud/LpjLKb87X2Ix1dNqGyijyuLrdI/T5b+FT5LmFDllXPJUEd9puA5OpzY9fP9uI0imqMwr60xlO+BZGG9wXW4v52OL0q0+cjTXqiTm1qsCxsBazeY435wIqs0PZ5jRSSGbfUFjN1O2xv8D9+FStO63CkqJ9tpuVekpY30NM13u7lrk32sB8rfhiKgmy+TvaeJQatZECld7BjY/DcjAyozQ1SCGF9nDXRd9yLWHXEHZOkqGqZZJaaRGQqykJYm17fft88LFdEZ5Fo5mHbivyasSmXLj3cezd7cM2/wCiAbD47k7eWGLs32tyvOTIkcpp5+4k1JJsVNub8Hfj8MMP5Hos+ydhWUyzE6ipb85SR0P09McxzfsN4NdCXeQxCUoxAsNVtjz5c4Bj8UQjewg2MrSsjp6pSM1y9JCBpE8A7uW9tiw4Pxtc+eHfJqaGlyTLhT1OpBAAHK6bjvHtt93yxzCnzvMst00tWi1MC2PdygB7ejDe3zw9dn+1WT19HS00Uqw1EEJTuZhvfUx2P+9hxAOUphNplkdTLGGCBRc3I3Bvub+e/Hr64G9q8wnpc/aCmhhSm/7Tu20lRoQiw4vfV0xTzDPHctFTG0Z21kb8W2wIeU3LMxudyxxjklANMXVg0prdJhWZaySU+JgAOFHH0xJSo1QS26xj9Ict6D9uIqajEx1zXC9IzsW+P7MEdJ06iLKtgLDANjLjucjl1IaNkatU4XSIolKgb6evxxbVSt9a3+WIhKFReDtttc480+rcDfGgLDypw7g3K38r24xiSQgXZVA9MVzLfYm2NdDaT9rqB3sTi1S2lJZ9XJ8sRM4KXYm17WtjyllJAJtjSQk7syD47XxdKrWZGI8IIIHTGpRWQglT6HfGjiOEixuTvtx9cas6gX1Edd8XwqVaTux4btsf0cQGrmUkANYbcnG01QkMqvwH21Dj54178ncCM+t8RCV3LtX7IIe1Ge1mbNnEtM1UFHdrAGC6UC86h5XwNp/YPDAqr/pBO2kW/wCrjf8A5sO2Y9vezmVVstDWZkIqiE2dO6c22vyFt1xW/nN7JWv+V1t/+GT93DRppiLDD7FAdRCDRcPdL8fsZhT/AF1Kf/QH72JP5n4v7ZkA9IBf/wB2DZ9qHZEf63H9xJ+7jH86XZD+1x/cS/u4L4Of+2fYqvioP6x7hBv5nqf+1pL+ZhBt/wA2ML7HoFN/yxLx/wBwN/vwZ/nS7If2uP7iX93Hv50uyH9rj+4l/dxPg5/7Z9ip8XB/WPcIUvskp1W35VkP/oj9uBWaewSjzOYTfluWKQbEinBuPXxYav50uyH9rj+4l/dxn+dLsh/a4/uJf3cUdFOcGM+xRN1sTTbXj3CSh/J4ph/9RTf8KP3sZT+TzTIxP+kMxvz/AEUb/wDNh0/nR7If2uP7iX93Gf5z+yP9rr/cyfu4AaGUGxGfYprvEmuFGQEfokup/k9U1TCYv9IZ0v1FMP3sD1/kzRd08UnaueVH6NRi4Pp48dEHtP7In/W6/wBzJ+7jK+07sk8yQrm6mRxdR3Em/wDy4ZJHPXrafZZ43wA+gj3XNqf+S7RU8qyr2nqCym+9IP38HE9hEKmNvy8+pNNytIo1AdD4sOMftG7LSiYpmyEQC8n2TjSN/wDZ9DiB/ap2NjUM+eRKCbbxSc2vb83yxlMgbyaTSGHlV6b2bxU1OYBmLstza8I2v05wEl9h9NLUwzNnUw7qLurCC1973uG2OGyX2hdmIaeOofNUEUiGRW7tzdRbfj1xrS+0fsrW393zeOSzaDaN9j9MU17SaB5REt6lJ9X7A8vzGneKtziaZm2WX3dQ6joL336835xRpP5N+W0N+4zuVWb85jTAsfnq49MP0vtF7LQsyyZsilQSfsn4Hl4d8Yn9pHZSngNRLm8YiABLCNzseOFxRDTgomShhtpSW3sApjxn8o//AJh+9jy/yf6VX1nP5WcG63phYfLVhyqfab2SpEgebN1VKi/dN3MhDW53C4qH2xdhV7wntBDaMamPdSbD/wDzgSGMOcI3aouFFyX09hUCNcZ7L6/0YfvYlX2JwqCPy5Kb/wDhx+9gpH7bewEs5gTtApcAn/qs1rD10Wxq/ty9nkQJk7RxIB1anmH4piCSO6BCV5jT1Q9vYnTMoX8sygDj7AfvYwvsSp13/LUt/PuB+9ghJ7dPZ5DEJX7RJoPBFLMb/RMb/wA93YDa/aBRfa5pZrf+zBeYzupvb3Q0+xSE/wCu5v7gfvY1PsRpyb/luX/hx+9gnL7cfZ7C5STtFGrAE2NNNwOf0MaRe3b2dTRmRO0alRz/AEWcW/5MTzGc2q8xvdDl9iMKsT+XZTfm9OP3sbN7EqZuc5k/4cfvYI/z5ezwuI/9I4tZIAX3ea9z6aMbj23ez8uyDtFGWXkCnm2/5MTzGd1N7e6GD2KwAW/LUp+NOP3sRP7DaZ7/APzuUfCnH72Dq+2LsM6I4z1Srmwb3ea1/U6NvniGT23+z+KZ4W7QASIQGApZzbn/AGPQ/TF7291N7e6Xm/k/Up/Nz6YX/wDDD97Ef/w803/3BL/wi/vYbYfbB2GnTXFn0bAAnaCXgdfzcSD2r9jmAIzi4O4Ippd/+XBD5Kbm91yz2mI0XbHMXFrySAC5H/djfCtlneTgJMkaXvazddulvnhg9pFeI+3uaU07lW71Gp3Xax7tbqx/qn7j93O6SqqEmNMsjO3eAaA2/PN/TGzUeKSOMR07iNmCO/C5TdGwGTzBe77ppeFdTgMvhbSfja+I2gI6Yo0ud9zWSJMAoZfzWa9mB24wcmmooUVVlVlbcW6AkAfefuxu0P8AqoF5ZrG7Ox6f+rFqPCaaHQG0O7k3xkQnqMFky/Ww38J6jGamCmELNESGjfu2W9yT5/ePrj08mtjYzzCccrlMge522soStM0jBVW5PAxgwMrEMLEcg4YMqzKKCkQwQROZgNTPY9PLFOSAVDtIgWx/q4GHWNly04RSQmPnlDViPljJiIGLGuNKsUpV9Z66SB9cSd0sia0YMD1GDEjSaBSqI5VEJvjepo3qKYNCVWaI94hIuLjpiz3Fz640ed41cRwTXRra9OwsL39RjJr3M8o7+Fo017xSrzu9NBDm/u6SQ1SGOdA9wZCpFyOOpNsLlXaZqpSyaIkFjKmkgCwFh52PTb5YMZhnFMtHIkUWo1EisQbFYiSdQB6G+/8AAwrtUtVVDxo+tgwWQvJbw+m37cfP9aWOf6Tjn3+69C13dXWmr+9p6Goad0qAvdTDUoeO5YqFO3JP6zcY6NRnL8v7Kw0sESrXSRmpcXAJUWVQSSCAfETYcHHMI65WrPeXPeSQ2EZDEa2DXBt5YaMu7QCromY04raqrv4rsCiKLD1Itc8nC4HfpwPuiY7lQwZjK1NUvJUgTFDr2uVsy2vfobnfFKerrjFMtGqSJpEjRC5UdLb/AF+eLT0jTytUGyQPGRoDBbgm7Lc87gjn/AllVVHRK0vdsryVDDQwPAAOm19unrx54e9rQfX0TBnhLsOcz1GSR0zCR2p5WQALrdzpOkCx2F/xwqS1YWoajRQsrsFKsDe99/XYm1sdCr6iPKps0yyenSYVCNNSSsosjWBuPUKeefTjHMMyjeqzA1CzEsCGJB34N/wGFysBYL5FhLrKYYUioYjKSiyykgEITpW+6gC1+L824wGzXu3q+77i8iHU5PhI5N9PTfr6Ymg7n+jTSs6KVXwC/UW6n0v/ABbFiZoUiqJqhnR3OxZhvfk2HF7+eOaG7XE2hBQt5VlWOWprJHXQVManbYkfIbD540oJ/fattEpjSBb2c2NhYddi1ienPpiemqaRKSJI4+9dyJGZltsdgoPxvtiqiTVmaLSUwF55dTELcnUd9xfqfLyxoa0E0Vakryss3equtowSQzAKsfQAnn9eCZgiioIpY6WJ1Ug2d7gsbc22PliOiyaaSthjmp1K08Z7w2IWQknffnY26cYMTxyQrGlMzIoVlKjfRpA5AP3bjbAuZR2DorAsKjHTJFFJXOqtHTr9oBtpdiBqNt73+mLLmCsaKbTCe5sxYrq1m97Wt1sMa0hnMZiKqY4SVkVrBVIJOo2556+X0t0UVGivAkgmmk8S6blQTe5JuN+MRjBeOUPBpTU0yaNczlBYr3aqbKxHPF+nX0xTZ56ljHJCs02i2kAgkkECzfTri9D2fea87GSGFibaksIwOt+eLdMWqfuUhMaTB0jDMWVPzha9wD03G3pjVHC8C0bWHlQ5XlDGcSGaKKPUNQ3+0tfby9PnguYqUGwzWZAOF0Xt6XtviksCyeNqq+kszLp2uBwBa46fhfbA85q4JCJFp6ccY2CxwiqsApq9r2aLD7QM6pO4ZmeVPtXFwPs12FzhNyQQy9oFeSZYgIGOpittQ8r83tx1wc9srv8AznZyEQO3fpexN1HdJvtz8MK9ZSvC/vDNJdVsz3/NBHl9/wBcSIMjdaXK8mwtVmIqHZwNDMBcrcMOtx8R0w4RZhktYkMNRMEnpwpdO7sdPNtrc3b6DCVTTJS0kc5ljLoTrCgnULdPXf6jGaKYVqSZgBNTzSHS6u1lJH48/U4z6rTMmFOVMkLAuryZ3SiAyRyMXZgQXcHw82H8X+OBXaDNKKJ46mKcl5BYRuABY3sbfpdLfLCRUVVVR0rAo8lOVvGp4vuN7/HEn5UlzWagpZJQJoFupCeKPpueoH3fi+KSUQeQ91tHH6IQxpk3gUU35VmDJDJNJPCZfdxITIQQrA8bdb35xBk+Y1NTm8ckpjipDdH1EBU/qta+/Nr/AI4WJ6loVjSMSyMrnvSAVLk2C7Hkbi4HO/rjaUqIngZ4kLgszd3YBwL7W2vYD4YyMgDHCRvI+9pzs3uTe2cpNWSVzzRrTwMEVhtrF9zsbdPPrg52SzihzHK6yCOPWXYksV6j82x/ZhFlzKUZbFSRsGj03fUoFjY7jz+fpiDKs8kyaECEeFxqJOzHyvjTHrZhN5zjeePkszomBmwBPGYTxZchkk3A/OAO9vTz+GKUna6jpWsil0BGpl30jqD5HphTrswq6iMPCJZaew1tUWYK3J62I+Iv9cUYZoa10VhEarUGuvhvzYlSbcH7sbtd4tO+9rto6Y+6zRaVjKsWU59qjQVVFFUUVKxCr3kjAAICwHkNj9cLYpqaoYEAUVRqWNAN1cki5JPO4G2LaV1PJl0VG+ZM7KfGgvoBuCOb7gE8WtsPPGcl7PJm9bG1PKT3bAqk2rS3jAUG1r7nz9NsceN79QS7krXgHCB1Ej5ZWSUlTRU802tYhHI2nWTcXuOBxzxt5HDb2JyWWPXmMSxjSDoiMhbSDtp1bAsBe2/14wBrzA2emljgvWMwikCkmxB5C9PgfX0t0Ts92JpKeJphLNUQB9IjVtLJJfxN6WFtsM0zHOkoDAPfqiAVF6DLGlqKeDV3iDVrIbS+ogG56bYH11bJk4RInWchgzvYHQzKAW58/wAL4P5o9blGb1NbSvJpmhUhpASfCdjfrwdsJmYNSKJkKSzMkhBk2Ku2m9wBxY9fLyxt1rgzFUqBNWCszzz5rmT1QmWqiiYkgE2I8I1Dg+R6fsVKugyuglliZGllTxd4rWWx6WO3p9d8TU2bVFFIZaeX7F10SLp03W97detzgPn06zKKlnEiyWUorb3A6c+d/qccVrXB1E4PCsuLisUtT+VJ4kkFj3xCAjgdDtiPOHlqakwRqEaMWLBtVhckc8fDrbFjLo/c8veXVFEWuoU+Jhv+za3+eN4KDTNI0lQ6yuFb83Vxc9Ou/wB2Cdg2EQI5Qxspqo6FDPEoVQwHRmIBN7C3kR8j8cMuVRZblzU9ZCztMIFKw9b2FzzzwNrDC89VWUax1gVpKdpQrIwBcg72v/VHUcffg7EkSwvWJZRPseVa29vgOvzwEoLhYKI90R/K9VVNJojEUADagx3uAbj1tz58DEFPXxyETRFwp8KEjwzPe9uOlxtfj6YW58wMInLySCSUaFUtt589L/LGcsrq6pnMFRIGSEPJouWAupN2PlfSAPXFwMdVDlWGk8JlioZZmaWFLRIxBJuBzte+/wDmMFqCCjjkY0yhGQBdajSZWJsAD6m526A/JWpc7qXpp0DSS1HhPdCTwqSQukDpYk7eY+GD1HUB6CnWeEIzszKASTcqOpPFmt9LY2xuDBR5RjHKLzVRlhURSQosZvoK8n9XHx4wPqMyPfrJGoCSLpba6mws1hf1wOrK9K2sMNPGY+6XS4sBbTYWBB9QPUknEk7LPJDRoqaDYMATe7bm1uAflvho3EeoqWeqxDIG7wwTMZm5QMAHNiL3+vP34sLBGFH9JiXbg8j7sVav3Raqb3dm7uNrIBdh4hza24+v6sSJQZjIquAQGF7G1x9+IHbccquPmintmqZqb2p56EdtMksYaxC6R3QB367eWFOorIQCk8kYaQkhWbd7NwTbyP1w2+2UhPalnkirql71ECsbLbu0Pl1Pn5cYRpURhqY3kDFV4IFwdySOR6+fphlgJEmXlbvN3vd0lL9kgFpAihhYi4HB9LfHFt5I0gjfU0cEFhIHGqwuPLa9zyPXjFbLcpniZqjvYwbsxIUNYADTvp35+nTF6NxVr3bx98Y7gtYFmNrk7i43vwRx16pmkxhW0DhQSTz1VQKVVidgAQzkgKoHS3POLP2LPDVRRa5I7ss43VAOv+9jFU7q609DSsmsXG92Pod9tx+GK9LQzrXiWaqV6UIPC2xV7723N7YGztUZjjlT5fVg1c63nM6xhrMANW56H1FgcYqXlszxmMl5CSZCbksLHcfAdeMRMC4kq3WSN1bQEYkbcAkAWO3Hx9MbSNpm0kO5Rl8ZIsLG+1utwebcYsDqidxRXjVSmC8sau5tpVbArfg+o488X6OogcN3jzRBkKqwA1A76b3IuPP9eA80niDySvI0Y0hEOzX63uPl88FaAzVHeCVE79lKpGD4Rwbk38r29cLlprUt2QtqyeokkpYu8j7gXkIjcWsTyy32NgNvTFaFISamR41ZY+Xa9xa/03xLJD7ujrVSCRyxRh+aRyTv1+uKEIDuphdiq76jyw8zvuL2+WAJa4k9EIwrB1TxiSKBVjZbpZSSx4Hx5GCs9VV5YKcGYxqxCnQ5vGgYbbbcjbrik0ktMonjKiFyAxO2+xFgem1tv8MZqpJMyqXpaULVyzSE/Zi/huLfAD1P3YU3cDTSrGeVcyemqM27U1tdAVXuA8xlVSxY3tfqTubX6Y6RkAqqJhHR1KtTuveRGoXUZGYcG3kAx/G2F7sfSVXZaJ55Fppa2ZQpumoRC1rKfPBHMcxqKgmarnEKaixCgLufO3Pwx1YNOW+s4KYCKyrHb6fMC1M9M8dZOAY+7pwRZehN+ATe97dMIf5OIrxT1EtF70sbT9wBfUALbkbAev42NoO1Xb5MrVqLLvFUtsb/AKHq37Pr5YAdjqp5s3nqKiRpJ3p5i7k7k6fwxU0TC4uOSUcUe52eFBVyh4lkd4xqbvDCh0BAST+cfjbGtFR0T/02sn7uKUjSivqddiL7W22I+e+B6PDMgaQa3Asse4ufiPjitUZlOadYfdYmaMkPNfaJb8XHPXHPMZGAg2nhGKuJJgIKIPpR9WuQWRV2vdufl/AkWKripZJTEWlktokMZIZTqFudjYA2vfjC5TrLLMtLSzS97JtqW4AXrz0tzfDpGrxZXprppEWMgKxuXY7je+/HngXtwAi21hDKhkIiAj76mJF0ZrHWQdWxtsD+OLNxY94uhUTcghgu1l2J4uf4uMZjoaaQmVh3kkRJ1SkDxXvsBsd/O974jjnilqkiXvBGCVs3i1MfCB8ucCBuUVLMqKYmnmS7SMWF5B4CqgHVf4H7sSZVIwqXlQDVK41noLedzt8Di8tfM+Xz9xI6KE0d0FAOxUG3Xi/8DAPL0r3qJDLU6Yoh5DS6k2BA8+T8sMPzPCaMt5RSmU5TPKzOTU1A1M+myrcja59Tcn/ZPlgpBWzwyU4lYd1Mmtmf85DsNXO25ws6m0lklN3lLqiWYaAbDYemC7T+8swkjUGM920iG7EK2o35tcbb4KgSCVVYyjuQ0M9fqkcBQrDvGfYxjax+/wC7jEb0/cZrdGQRl9KyavzwvLeVht8bjF2inamjkooZ51hWRGdEOgMCii5HJ5O58ht5UJtS0mt3YC4Uad9Qv57f1VF/LG70sGwcqwKwtlphJIju25e0ig8cb9Bc+K3XbGkkFYzsUpqxlJJB0HcYJ0WUTVayxQm7SEIAoJEZ4N7E9TbHmzWigYxS0qmRDpYrTIQSObEm5GBj07X252FAzdwut+0L2ZZL2q7U5hUw5q0OYsytLGrrJpIQAXW4YbW645l2k9k2a5BQVFWrwVVLGuuTS+mygHcq3y4Jx2T2n9hYe0D1FVSTxUOZNOlqmRrCwX1I6eX0OOVZrkXtEynLanLznBzqllGhu7nSQhfIK3i+mGFgq1UrRnC5/RkGUq2o3UlmuQBvwBf78QyO6zpAgZmle5CC2qw2Xbra334xXzy0EjU0lKY6gE+GUaQCLEFgd+QcbSzTUwh96g76W5sInARL/Dfy+h8sZjjhIDcZVyrzFYY3X3fRc3jSNfEwHn4rm17b4Hx1dTVz6VX7BtJJYAta+46bc/ji/HGlXAk1grOtjI1lFgd+d+nl0+OIBq97cXgVLk2Dggr6/dtiNOENlQ19dUGWndIYioYi7tfYXufuxUipnzBIqmoep7o6j3avz5MLW6n5WwShkRqkwNHYupAZizX5tYkWFzcXHnjxhWRtTBSV3AYCwINjYcdfT4YImgmjGQozFZHZD3JUeHvPFfc2tycS5Ss1ROshaWSWVlZUkPhk5JHO2xFsF6HJ6mJTXoYKl4lYd2y3ABuu/QeYB8sEuyVG+V5zTydzSyyQMGkjls1luLnSdtgfS5HXbGYSseC21QY66VLP8uqKKoSnqcvlSVpmu52BIFitiNxY/fvgBOklMNcpEQcjQq30ki/Tytjp/tEznKO1WcCroKiKCnplXv3mcAEkfmqSOABwML1HmvZDJpPeJZYqysFtMhHhT/ygXta3x9cSOBv8p9KvyjdBUsp7LZrnKo1UfcKIcak+1cWGwB3A+O3xw95dk9PRRd3Sx6FsAztu728zgCntDyyUM1PocrvZuW38jb78V5e3MdagR8vmq1bUrQRu0bKdrX08/ePhjpQNjbhgshTZt5RnMc7pKGXuKRfe6q9gF3Cn449mfYbNs27M1OZJmVswvaOCNDpA3uqtxqPpfj5459VtJHVwvUxCnpZJ1eMMSVj3Ng1hcgfDDxmPbLPPsqLu8okjplKotPUBVA+b7m/GEyawA0nsgJyuI1dG1NPJHKrLIrENq5B63wV7IyaM0m//AFZf/bhmz3Ia3tjOtZS5VLTVLHxupBSRd/EeN/W++B2UdiM3ymullrJ6CFDC6eOcAi464FrtwBKY1pa5KyOsqFdRAdbGxtijAphlkhhiMki9NV7i2/NufK+GGXsVNSUss753lZaJbhIpg7OfIDArLKk+I1YeGJWAIfwam8v2jFOF8pb2kZRmKAPWJUQ0/dPJHZwjKVFhxbqdrD0OJsxGYS1UUKF+6A0kabBT0F/IAH64p0uYwIryaZE0E/8ARi1wByoPW4OLsWZ1ksxUQFFYa1ZeSOgt8wP42yva4HCSAeFcqKfwNPshD7FtrsLC/wBTf4nA7vFgK6VYsltNgORtfz2ANyB0wXrYGEFMWUqxe4AuAgBN/lf8BfASXU7VHeTqFjZVuRewB8bb8nw2GLYA4ZRABeMEgkNEHgQKXMja7MnFjfbbcYpz0rU8JQBnAjDF1BPGwFiepHO3Pri5ltXFNV1Ska3RGLhlC94o8NrDyPz3HwxWq8x91WOohcvclWjOzMCRYcm2w9MUabgBFVLGWVMfvJi0B2jZplQ7KFI877HYceuIWrTEIlR0kMkuu+u3gB8999/u64p5m3u1ZUytqMrKVibUNxwPW2nFNKhHp4VjlTRdVcDw6Gu2/r08+MG1xcEQtOdGaiSWWoqCzPTwhWAe7m36Nh5C29vni3TTjuO7ZiUDuy39bWNvmv1wNymYw07rXNeYmNC6mzMVFr368fTE7VDpSJvrEkpEjf8AdLtufPf8MW13qyhxdhM+Q1zZZ2deogmiarBZUjmG9/MW42JG/wCq+FL3qMc5e5Pn5/fglRKBDUKymR4V70x3FhcgbEX87/LFJ5Ed2fu38RJ3j3xoMrnY6KElfU/tkpZqjs9MtOJDIapCNC6jx5Y41TZbnRdY5MmrfEQokWBtJJ89tufUeuPoPtrVLSZfPPoMmiYXVTvxjn1R26pqSCOdqSoRCQCSN9zbgXxoEQcNxKa6TYUrydhc0rob1WVpIUF1V3W/wXyvjbL/AGL0sqSS1UrxCQeBIzut7bnUOecMvZ3tUZWqffqaoRkcKhsfELXuNTEgHbb/ACBo9pqHayTgGxvo+HrhjNK0jdSU6UO5SIvshoYMxhijqalqX9NGPIGm4tpt1wUk9kORzqytJV2e+vSyrfYDou3J+uHpJIpCCsgI5xvdQb6h9fhgvhoxmlQrokKD2RZHGEbXXBkfVvKDc3F+nB3+uBWeeymjWpIoHmlmeF5I4mKjUQ6E3aw51HHUxp/rD64C9o5/dFpKqGoijnEyQoHawcOy3Hx2v8sA/TsqgFZOEm5N7IoFEj5lVTF9V4/d20+Gw5BHO5wUzD2c0k9ZFMMxmgaRSilWZWYLwNmF9vTodsO4YE3DAg7i3ywndu8zkNRleW5eY2zKZ2MV/wDsgRp7z0sCbHAu0kIIdtyo3H4Vy3MYnps/moGy2mrb1PclPEVZydyW/Sa1tubm2D/Y7s5SdoZZw9LTUxWR1T+hDxKpAuGJseTt0thyzTsLBN2XTJqOaOOVG77v3XUzSAHx87G9t99tsD+wczTz1GiOmp6uOR1q1ZCWL3G/TYjcfFsXHAGHajcR+JvRc+7dZSmXT1tAKKkm93KL7waaNBumvcjcX3AHJthaOXzx+8yB1gnRZSe6jCkFTbe+/ljpntMoveo83WFJC7xLLKVj1amCqqqG5W+r52wkPlzrHWXcmVYpA+ob+Jr/AEIUW/XfGJxcHuB7/sn6ZxN2luGmWrYl6qZWLKABp3DO2/F+NH34vZxkj5ZG5iq5XMUbSAsdx4lsDYDf4/D0xUylVFLoVl1Tin2JsdX+A3ww18UubPUywxPJTvG0esKbKxswBNtsLo2nMOcqKoyysmpC9Eqsu3euz3Ee5sAPPn6fDC3R5fBV5n75oWQjwsQNmtubrxjo0dJCOz2Y07CV/tEVhGfFbWQLbeTH44oZX2Epags9LWsY4k8DqQGDNsdacjYfDfnCZ9YzTAmQ0MfVMbC+V42hc8iyWlqcxcMEjV5t9PhG53+WNM6q0yXMp8upIYJhDM2ozp3lhc2UX67fxvhuzbsRmuRstUFWqgDEu8N9SCx3I5HxFxhI7XU3edoszqwAsZn1IQfzhbk/44fHNHMzfGbCVqA6IU4UVfPaaqhp4JKWKkTSpaYiMC51EbD4D8cGsh7SVEtP3clXFIzyBypQDQuni3qR5jkYSXgzKXK6eaBWl1GQyBV2vqAFvXnF7LaeehpZpqiKOLVeNml8O5BNwfPp6bYkg7FZTvTJUZiv5OWdnh7yMnxMfnYDodr2PngXWVqVDzRhUAOpAAu4YsACfjyP8MDTO0mU1MhiaJJZe8Daib24AF+ev+WKUdV32btLNI5BZCq6QRbe3X/awsWASlgdUwU0nuczaodM0qOPDDuQlz0O24U/TAKtaWScN3TqH+0V3OwFzb08sEo3K19WWkaINHJ3R1Ha5sOPuXz+GBlbmhrqinhdYpQHKXjBUkELb4DYfTFl24UEfIUGeTtLCkYYPUot3IsfDpG3G298DssqIqaqiWeHWHUA36XN7/fiTNmWqq5CkTQi4JLtff5D0OJKaANUQGcxgoQLBrKwG536bYJrQG0iAxSZTWJFTSoWXTHH3pXmzahz6gkj5Yn71WjvLyRfU55Op7C5PpgPTmUwzM5Ld6nc7Hc7g7E/AeuCMs8f2dLTxJNI0RJJXULqT06C2o3woMF8ICykyujQUzRKGYmJ3k2IsBcgb+oH3cYD+8R+Q/vP8cYpa545l1BqjvgIwSNVlK7jnjf5YryRqZGPdwm5O4YD7r4cGYyhcO6+ve3OWRZ1nQyvL290zCpkvJUo7ISFS4BI+HlhSzrstnmRmKnl7T1pJFwkNU91HrcWw15wy5f2zrawygyQSxyxx9SHjZLfUE/TAfMZZa+oeomkV5JGFiD+aPLHXj2W0HikEjiLI5SRVdl5ah3lnzTMJHO5Zpbn8MY/0VjFg1XVv8ZP8MMNQT3UlvI42kRg3pjoCJvZZPMceqB0/ZemaJtUk2xuTr32HH1ONTkFP1MrWHWRv24s1/aOkyyc0stNWTSc3hi1BfDe9hvxvxi7l1VR5xGXoplmQbG3KnyI5B+OK2sR08oJ+Q6ZDG+mWxawBlY3tz1xUbJKeWejXQ1jOpbxkEj6/P5Yas3pBTVNPChLsqKXCi+jrb7739cK7ZtSpVUziYOkTa5GjJbSLHm2FnbVohd0n2u7RU3Z3JWqpbLHCgSONdix2CqBhFosxmmaprarKM9nzep8YlipSFh0lSqqSQbC2/HTGMvzFO0+eJXVSye4UJvSxmNtMjcayQN7Eceg+bl+UXkqoGSpaNbMSgTkC22+/QYyk7zYOFoAoUVTXtlmEtTDNH2YzJrq8Vm0Jdid+v8AsnC32hzPO8urJc+pMoly0d0IKmUyo+pQLB9I3upI+Xzwx5xmksMKSmeFhHUq6uYj4E1b336bbjyGBcnZmmWeqmE8lWDBItLFKPBEXLFuTyb8+WEyccp0QbRJ9u6AVuYZlK5NVmglp6iMSsvdANKlluLgcWsPphUqKXPMxrp56bMKeEStqdU7iTY77kkm3AtgpUxJ2ayQ09bIocGQAqQxW5GlVJO/5t/lgbFlFHlsdPmpzGrWiki72OUUmkF1sLFtd735FvvGOPqJdvJz9VrFhv8ADClyXJs8oy8tU9DUwBHIL0kQQKAP0rW52+vlif8A0xhlrJYMuiTQSzd2Ie7jC28JsBvbxbffibsvTZdPT1cjTw1aQI2p1DIy3HkVG/O9ze+B9L+QVy2rnnOqr74oUNwo87MOgYj69cCyV7G24Km+oW5De0WfyQUuWy0tTXRsI9M6KzAO4YvrI1ENvfnDXk+f0+awQz1lTFR5gbBK2C4SQ9NYNufv+7CtmIySpraMz10ivKNSuTqF9JXe42FwL79T6Yp09ZDT5ZNl80KqiLpjK76l3IFgb8GxJ6gWxl1EbdRk2D3/AM5HyWnT6gtG3kdV1mLOJaWVIc1jSIsPs6mP/o5L7XPlgN2q9n9HnsctTSlKWplA+0jH2cv/AJgPidx59cJ3ZrtjVZPD7jmkElTlmyaJY2YgC26mx9eT5YeMvmlSn9+yCo99oyftKOX89Ph1H444phk0r90Z2n/ifsfkcdiukHMnZTxY/cLj2f5HXZVl5yzNKLuXhVpEk1FlYAjdD63Ym99z0wHqlYUgQS2cr3TKb3VlCk9OpBGPogS5V2mo3gkjjlBHjp5Ruv8AHmMc57VezKphgnmyjvKmGUiR6eSxlQjoD1G/+eO3ovFY3nypxtf8+Fy9T4c9v8SM7m/RcpXvIlWUSyEIfCGvb1/ZiSrzAyVgllTUNI1KgKi4G238DE5pnjlIlMUdjpKEXIN7W+PPOI2XxSFY2UPwSNwQeL/D9WOq6lzj81bOcRxdzPTwoWiCqyksO8Nj0U+vQ9MDYGehlSolj7wh94ySDt+GNIl70hFNzuLX64nWomhsxCuY9kR9wPPAhoAoIgDVLxniqqjWUSHSLjn06fL435JxI6grG7jXckBU30W89rdQcRBXlUxyAoSdIAF7H44mbVDKgaLS6KNeg+G37ecW7m1RO0q3TVNRUVWmGeUICLoW8AHUEX8zbBOKvK1kjTvGYmGmwF7C17m19uT/ABbAI1UslbKkegoNj3ZuN7fh+rEsLxuGAfQ2vSZDtbcAfA7+eFFu4ZVAA/iRFp4vfJ+7KsBGQptsd7m38dMXRmLKNIpaSw23Tf8AHFCCni96ChmZFWysgAHwPyxfSFGRSZkuRfYjDG8ABKL6wvqv2hWq+0k4NTZoi0QjU2KMyrpY+Y26DrinBEIMtioo5SxiXSWZSGJBtck+e/8Alir25gpaPtxnFdWO0cPeI7jfx2SwF/W/G2JxnEVSZNfgcbhyu3F/nvbEimcZWtPThVJkFA89hknoJYI53iY8uhsdsJ0eW1yQVYqKuSaChCzhY5XSUoDZjceVxcjpfyw6ZnNF3ZEZck8luuFWqr4KCtSrnYe7kFJATYMh2I+l8eimaCN3BWbTvc07eiFe/Uoy6eSnqpqiqV1ZRLM2uZbbjVchSCBxYb9MU6LMPdKmSamrq0SyLpbRONTemoC/FuvTA6p7iKqdaSoFVTLKe7liuNaenkbD5HFJ4q9t5czkiXkjWBYfLHOE1rq+XSalzeKKbXUazMNys0feMeBbkk8H7/M4Ddou2glgFLTQSGYNodX21mwOx5Asdzz9wAiriEVC3d11W5Oz77AeYOIsvEnu6RnVMVXSXK9Pl6Yt0xIpJdG0OyrlNXZkzGaSumhBAAjhldY0HkBqxfizWuZbe+VJF73aRjb4XONKake1wm49L4n90dd2isBsSTjO455TmgLyySVDEvUXsDdpLfTfBPs9QQZlVHv1d0h+0fwqF9BxuSdrbdcVKajnlmWOFLvfYAE4O5aacUiUKVdPTzSEyTSzNpUkGyi/lbf1vhTj1TAqnaCo72Vaaty/vgrF9NQpUXIHBFm23HOKlDFlswEUmRqka3a0dTpUC3m9xhlioKns/H70MwNXTLe5DM8J+m2/r9BjLZ1T52l6ylpmaNCxshJ6fpAj06EYTymV1KpTUlPRZMIaSJIFkAdg7KtjYWBIAB6fT1wCqJ6ZaRrVEMjlrBUDWI24HTp0HGLnanMhEaejQ+NR3sm9tzwPp+rCZnWa10BWSBp40C2tGd23vvg9jXGiUtziG4FotUOhihYRAMNS6bcDm/H+1gZNWRQVKS6kUkaWJDXVSbbW28vrhOqWzDMZSsZqQp2IJI+7jDNC8s0arJGdVuTgZI21QKkbjzSKFkcgxIWZttbMuk/7u/7fTFaPNq7JKxKuhmKMB47XCsL9QQP4OI9U4I+0b03JH04xHMs0sbxllsTf80DCHRBwpwsJzZC02OU+5dnuWdqJFLsMvza1xIuwkP6/x+ODaZxNROKbN0KP+jOourjzv/BxxhIJaMEBmItYEgEjrt64dez3boxRCgzpBV0jbCRt2X4+fx5xyNX4YWt9A3N7dR/tP/RXQ0+rDj6jTu/Q/n90ydo+xeU9qIhUMBHORdKqHr5auh/HHIO1HYXMcidnnAemNtE6m6EfEnz6c7/PHbKGkFOhq8oq/eKOTxGInUF+GL0sMNXCYpY0ZHFmiYAqR5Yy6bWzadtg74x7j8x0+nYo59JHMeNr/wBivmeShMdmjBAB8QJuPj+OMS3MkRkGggDxEH/H9uOv9o/ZlHKry5QQlzc0rnb4IenwP1xziuynMstnkhkpWSUi2mQfmb9B8jj0Gn1bJ27oza5E+mkiO1wQlC8ch0zDRHfS5bYk9cQMrVTEI58ZVbkW1ci5PN74NHLKYxxO3eJLp8UhOykrv4fMWPX5YrPkBWEPHLLIO51XVRpNmHB+v3eeH7xeVl2kHKoQSU9PWRxRvZbHWzJsdtvj1xbo09wkch4o2YEoGY7C+x3/AM8aVQgkBdrmdW0hV4UdDt5/xti7JJST1Kd7JJEyN3Wo7oV8xcX6Dpi3UUp1qcOJ6exVVj7oDwq1gOOnXw/fimMwcAWoYSOl48WhGbJHJKFieTazAMwJ9Ph0xsZI4yUElRZdh4L/AKsMaCB6QgIzlfXftAoafM+0U0FTTtORKGQaiqrYLueh43HlhPr82ostgYT1SpFrZ9TkFmbrbYHy29Bhq9qUr0K53VU7FJlk2fkjwL545F2Ny2lzGsnnrIveJBZg0jFjc88nGfR6gMe91ZBI+n3THx7jRKs1vaGqzGpMeVwuF2GuZdj/AOVRuT8TgDJkAzAd7WzzVEik+GVSEXy2FgLWx1fMUjy+H+hxx0xaUxkxIEOkG4G3rjn/AGinkiikZCFNubC/OJD4i/VM8w8I42NZwEnx0rUkBjaVXjkkZoiqkBUHxJNr7jc7EYH1csqy2jAcEfoi5+flgtI7S1BDsSCAOfU4KUUSQRh40VW1W1Ab/XDBNZpO32dqBZdllbND/SojcvdARyvIuMMFLRtGFDaEI/rCxHyGCBRQ+w5GJpYI1RJADq0je5wzcSMK6uiFqChVUZQTwLLv9+MPEsVh9mCTwenxx6J2BcgkEDnGJoY5HhZl1Eje/wAsIeX7qBQHddBemdkQCeJo6YP43hbaUf1WBF9O24GLmVUUbtPPS5jSzTTAgrNTCwPQC97W25FsVmleWiMTsWjSTwqeBzilKdDFVVBpN1OkXHwPODF8ORgkGwismV1mXRGq/pCzFhaSJgVH++pso9Nsa1eazR0Mc9SQ0buO8YgF9PQFgLtfSeT+lirl2aVqVVRCtVLoSMst2uQd+vONu1PiyqvY7kQ7em5wxjP5lHvwluSU5jVy1MjDXKxY3PA8sSLS6gGF722xplCh40LAEnk4MhFEQsOuFhE26tC2oFfwkLc8nEf5PCjdfSy7XwYsNbi2wNsbaFCnYdMFSNBfyet9gAw6Hp88RtT/AKNvqcFmUALt64jeJC1yo2wJCiDS0W502t8OcUJqAKQoDBr7E8DB1gNZ9MQEB1s2/ixV0rq1VybO8y7OT95TPri5eInZv2HHSMn7Q5f2khvBKIKpfzo2t948vUY5rKi6iLC18DmlkpKrvad2idDcMpsRjBqdC2U+bGdr+/f8+60w6gsGx4tv+cLs0latNIkNcphdzZWXxKT6den+eJazIvytSASU8FfSyDwspBsLcg3BHywJ7O1EuaZUklY/fOIdQLDgkW/WcZ7IVc9PUTQRSusSzIgS9xYoDjkHRXCdUw7XC7rANdu30W46jZJ5LvUMc/NCKr2Y0qtI0Qkj1bETxlxbyG4Ft/XAbOOxk+T5fNWRmmiiAtI0N7gE7bEbbnm+O2J4xdt8J/tIATszmDKAD3S7/wC8MY9N4lM97fVYJAyqm0cRBNUQOhXCqigpqWCWWCIytOqlCHW0Rubki3NrkDpfrivS5QZgHqYnexLMTybg24+HHocEtRaIMeb/AK8EITZB6ob/AEOPZDheaklHbKBR5asaLrB1AhQhso+vU8ffijek6ut/Uuf1YaZFXTewurAD6YjEUdvzB9MNYe6EPX//2Q==" alt="Property photo 11"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,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" alt="Property photo 12"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,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" alt="Property photo 13"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBAUEBAYFBQUGBgYHCQ4JCQgICRINDQoOFRIWFhUSFBQXGiEcFxgfGRQUHScdHyIjJSUlFhwpLCgkKyEkJST/2wBDAQYGBgkICREJCREkGBQYJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCT/wAARCACbAPUDASIAAhEBAxEB/8QAHQAAAgIDAQEBAAAAAAAAAAAABQYEBwACAwgBCf/EAEcQAAIBAgQDBAYHBQUHBQEAAAECAwQRAAUSIQYxQRMiUWEHFDJxgZEVI1WSobHTNUJzs9EWJFJiwTNygqLh8PEXJTRjg7L/xAAaAQACAwEBAAAAAAAAAAAAAAADBAECBQAG/8QAMBEAAgEDAwIEBQMFAQAAAAAAAQIAAxExBBIhQVEFE2GhFCIyQlIjgdEVcZGx8PH/2gAMAwEAAhEDEQA/APS/0tWfYGZ/fp/1cZ9LVn2Bmf36f9XHlLhj1uWkgyynp+3gmL1FUixKiIwAFy+9lJt05eF8aToi5zU+oQHt17Rou1FgQxJ5jwANgPHEW9ZN/SesPpas+wMz+/T/AKuM+lqz7AzP79P+rjzNVyPW8P5fHUN36SMukKyHWQOzsFAXa99h/wBiyvR9lf8AcVIr2eCKV2MSJoCtYaQP8IA71t76zjip7yAfSWf9LVn2Bmf36f8AVxn0tWfYGZ/fp/1cDAu3jhN9JyLJTZZGwG8khsR5Lv5HzGIAJNrzi1hiWL9LVn2Bmf36f9XGfS1Z9gZn9+n/AFcUyeEaFsoy2pknpqk1sXKp+qcyDZhrNlJuLb8/A4Vs+9G8XayxJE1LLfuRzdxxtzDbA++492LbPWRu5xPR/wBLVn2Bmf36f9XGfS1Z9gZn9+n/AFceTKmbiLg+QyQyyRrSx2SMOY2iW2+523O5uN74aDxVW1WU0xzbK01Hs2SlB0PVAHVdwB9WhtsBYsOg547ae8nd6T0X9LVn2Bmf36f9XGfS1Z9gZn9+n/VxQKvk+czSUNLPPkma3IOUZu2lrnl2cxsG8g3PxGOkOVzUkhyrMYJIqoDXCk66WbxsP3gPFSRg6UFYfX7QTVSp+mX19LVn2Bmf36f9XGfS1Z9gZn9+n/VxSVLSLFCI9I1jniSkKoASMX+EP5ThX9Jcn0tWfYGZ/fp/1cZ9LVn2Bmf36f8AVxTrOi8yBiLUVcSLzJt8McNGT90g6gDpLs+lqz7AzP79P+rjPpas+wMz+/T/AKuKF+k1DAArv8bY++sFpNMhZRfr1xf4BvylPix2l8/S1Z9gZn9+n/Vxn0tWfYGZ/fp/1cUePqpQ8tnGnUurYL15+OIz5kZNKse7IbIo6n344aEn7vaSdUB0l8/S1Z9gZn9+n/Vxn0tWfYGZ/fp/1cefpc10DUy6WtYqDz+WItRmMksmnXvcEKOXxOLjw1vy9oM61R0nov6WrPsDM/v0/wCrjPpas+wMz+/T/q488KWZwh7xPQmw+XXGS1nZjSmksDp0+Hwx39NP5e0j44fj7z0P9LVn2Bmf36f9XGfS1Z9gZn9+n/Vx52aKSa5mY6b3sRsPdiLJUQU1wid4Ha1sSPDT+ftKnxAD7feek/pas+wMz+/T/q4z6WrPsDM/v0/6uPLtfVVdRTTdnq1aDpUEfjewtiuM8oauWvneplC1bi5VWCg3Oy7dfPe+FdZpjpl3FrwlHWebf5fee6fpas+wMz+/T/q4z6WrPsDM/v0/6uPCVJp0wxBlRwWZY1YLq07tZvHa2/XHf0d10h9KnCzqo+uzmmj1Rtq0oJh3Sbb7kfLGelYsf+/iMCrfpPeGX1yZjSJUpHJEGLKUktqUqxUg2JHMHkcZiLw7+y1/jT/zXxmGFNwIQ5nlfhSmmqRLGyTkUUbJIgLRsUUszoLdDYdRb4Y5Z5Kwp6ydUeGKWokijT21VSNjq5my2PW+3x1yvOEy3K545Ya2ISAKpWf6lWYWJZLkXIbmLWNueNmjjqaWoYgxxSsweQLpSNfasq352cXtboPC1llTmMAmynO4myxKcRxwRQh5o5NQqANPT3h7jmdCjFk8H00b1KyQ5hIRAC70+kR6iyqA2m3s9Lbb26jFW5LULFm1JmL1cS5fNoUKVBJaOwBAYeyADzt7YPQ4e+GMrTOM7llmrXjjQSPDArHtmVipNzyC2K7bHEdJNgMSygOX/dsJnpMNmysbcpT/APxhxSwsBthN9JX+0yz/AHZevmuJTMq2Ik+lKtlouBOEeymkhdpZn1K5HRtj4jvdcKWR+lLN8phWkqVTMKMc4pANh5A3HwGn3jDL6WatKThrg2CaljqYZKaVyjMyMp7u6svI723uPLFbpS5TXA+q1slG7ber1y3BP+WVAR95V9/XDSAEYgGNm4lzUUlHxVwvmuYUlKKQ0S2NPVDXE0zKCose8hOpeVx54q+nrq3Nljjl1doQWram1ix31AeA2tfr5Y9A5mtEOE8yNCIRf1ZZDGApLAoLsOd/fimKqOOOapRFVF7STuqNr3PPAkFzCseI41OWU2Y0EdJVU8VRTBBphmGtV2/dOxX/AISPdiNFS5rlVIaWjqIsxy0bnKs2csi+cNQN1PhcC3nzwUi9hRuO6OfuGNxyN7fDBSoMFuMH0dfTVNQtLFJPl9cwFsszduzdvOGf2ZB4X3O2+JGYPLTSmGWGaCYblJUKn/r7xfHSpoaWtpzTVEMc0B37GVAyX8QDyPmLHEdKbNMrg7CjqUr6NeVBmTNIi+UUvtxn33HniVZlk2DQfMZWOrbnzvyxxekmqbhE1KlybtbfxwU/9vrpURHlyyqbb1LNGGmT+HUDut8fwxrX0j0bSU1VDLQzEBiHFtZFvZte4w3Srq2Iu9MjmBZcqZRrLqN9RexsB/5xLeqhcXaOMtAShA7pv4gY2qJp5lVH+riYG6W32N73PLlgPXStUMzklo2a7X62G2G1UtFnYLidKnNGdYVXs27JSbqbi/8AXEM1mnWS76WBIHVrjHNXQHr3dwoG17eOOypcqrkMb308wMHCgRY1C00Bk0h7hL7XA3Ivaw8SMdoIQqlYkOpxYte5HniXTUhKF7qpUbOR1t0xssax37rSq3eIA0i/nji3SSAcmRpGVR2Wt3KtfsxuQfE4xaaWJNcgSFTv3jcnHZqlIFIjcqxH7p/1xBqHMp1M5c9Lm+OVSZDOBmd56yFAVTvnx6YHSOXbVYC+N9HgMfeywQLaAZyZzQE2HPy6YUOLqaOnzqIJDEiyxBxoIBJ36W95vvh2VLEbYUePSv0hRKEVmCX7t9RF76T4873v+PPP8WXdpjGtCf1LQAYKdIpQ69sdVwQPqxqYjfztbYdWJwf9HdDQU/HvC0z0Msk0OZUkSBXOmIvLcOT+8bcxvbbffYDmGYSU2pVFKRPJG8kQSxVdh3eemwFrn/FfDDwcpHpD4Od2np//AHWDso5GuXDTLyUbAFd/IAbY8jTuG9JtBe09jcO/stf40/8ANfGYzh39lr/Gn/mvjMPrgQxzPK/0VPVQRUVNW64amQgtKhVVJOokkWNrEC9tiRztYQpIc0Zn7CieaGKHtQiDUsanUW03tYk33t025YXn4izClmE8FfLTma7R6ze8Ws2AvfSL6iD+WOUfEktLCtPTzS0EraVadXIeRACNDsCe7Ymw5bnAfiBLFe0tDKI0TL1zSujEkdChZUClVhS2kjTsxIJe1ugPTDzlGZx5FmD0cMv93kmLzh432XTsU5hVAVdvDwxWeRcc0z01Xl9bm8KmsLCdKumkdijIe4mx1LqOx2IJ5G+zPwhn/DcXBcNRmdW5rIgKeRpgWnMhChtDWJflsLEdNsESsrG04gS5FJGx/HCR6S2U1GWqbi0chuB5r/THLIPSbkmasiUFeiv2ZhhoHhYNPOTsQxuQoKsOVhcYG1uZ1nGGbVEEMMMv0dPPTOTKIyhDDu3GoMb93/hYnF0cXlGFxxA/pD4cq+Lcj4a+jyqNl9GVkE6NoOrT+/GHCnu8mA5jcYrV+E86o5BJJl80kSOA01PaaNf+JLgfG2LNesqUml0TVxejVVDK2rT3tOgSRkgWJ5Gx6eWJseaSyVKxSNS10yglZEYCoUX3tKhEq+HPDKVSOBANTBN4+cQUUVDw3VRwh7PJDu7liN02BJNgOg5DFJ17H1qptc3kf8ziyXz96zKpKSapnLdpGFiqHWRtmuSGKhiB/mviua1C01QfZUSOLkgAXJ64hPWWcx3j9ge4fkMbjbkcfAhCAgd3x5g/HGDng0AZ0Xfx+GOijfa2OQ+OOgxM6Rs4qaSgyPMq+rolrI4BCDAxAV9cmknfa/niLk9QuY5MtRkdTMmX9sYjl2ZQmeBHA1WXfUnvTx2Bx24gLS8O5jRQyCOpqexMTOv1Z0PqYMxBUXB2DbHGcH5bU5dwoqVNMsEj18jkRgaT9Xa4sSPltgJHzQgbiQMxjiAOrL5aWcW1wJMstPIDfvpJs/T2XUHfASeFtRMg73SNRyw25mB6zckC6gEk+/EWloqSaMvLL3uoxq0HKpzEaqbm4imtOQO0sA1+RG4GN49Sm9ze98H8wy6miQtHIG3tz5fDAggqbKAB4254dR94vEai7DPnrSx2JQM2OE9TNPsSVXwBxv2LMb46LTE88X2qIMuxxIQiPhjcReWJop7dMber4tulLSEIvLH3s/LEisaOgpXqJlbs0FyFtqI8vPCxxBxNoyRqiGKpgYsh1K1iouPAHz8rDnvbC1bVJS4Y8w1LTs+Idj0SOyI6sye0AQSPf4dMLXH0z2pEj7NjDc3ZgRflbxXff4Y+ZDUSLW1X0fQiOapElRJJGRLr03sqkkC12HOx+GAldXU9XSzx1lOwryRIxhjQIrHYiwO5JNz7icY+q8Q82hYDM0dPpDTq3PSCKqPt1eFyqo4OqSRmZ2QXAB7pFr2+IwS9GSsvpI4eNTVi75rRFIyqksRKoHu68sCKhauVmaOSKKWNFsHS5JO2rlb2j5W54mej2ilj9JvCDTvE9THnFIkhSMAG0wO1gNrW3IuT44wqQ5mha09zcO/stf40/wDNfGYzh39lr/Gn/mvjMOrgQpzPHNDLkdVl4paykjR0DRh2Pslb8yDy36YECKGXcyxHs21aZBbS2m3tG/I2239rAmjglVGklMUae1oB2U2BvdtyL6b7YN5jw9TUvDozGjr4q+pqIkSVVYI0LAanUIpBa5ci4B9m98Z5RmuAeBDNUbBGZsZ5qzaGMBmJjusKqNVgTpe3gRb/AKYLZbxDnWSUsUkebs5p43dIpkjlihV7aiSb2a4UbjpyGF/JsvmgiRauqWmZljqIzI7BShvcg33PTkQLb9MZnlKsNRMlFUzTm2oLKilNGm1iSbN579MCFE08GVB2i8bRxFU5hJBLHltM0lMojNTFAquSovquSRdSOYAJ1c98OvDnFNDwxwWhoMmqfXKlY+2qXdWLSWvfST3SRqN/G/XFT5HnFXllKKCQFEmV13UB0DLtpcXJ3P71vfyxs1bW1OVKZndRLGJFlde85BKjfz38jbwtiwqGmDtN5G4W5EL0vEma0frZp9ISon7VzCxWzlt9lIA2JHI7sfHDxlfpB4Tp6VqSXhqCJdRJNMqMrPc7jVpPP3nCdkbZL9GVb5lTpNUiGTsdcoTWtgb3LhTY6eViN8dcr4eyWpyUnLjG80R+qklrIowpYi3+02IW++55G3QYLSFQAENILdhLMps+4TzOaOCjzWeFmsNMkg0Rg29rtuXPkDhMzOFBW6301DRSa7jdNQN9ulr+GF+T0Z1EsDVlVRqkELB2nil1xz2I1BGuA43tceY6YIx5hQSIwWoRFibSyvtpt0A8h4Y0ab3+rgwTi2JbLz5HUyNJJRSUcjc5KZim/wDwmx+IxsMupptqTNUkPRamPf5pb8RhDqePsqerNPQtFM1ie1qJ1p0B8O93jzHQYjNxLnaSNaKgqFb/AGYp7ThPA6kYn52wUuBxeUCk5EsOXKq6FQTSdsv+KmcSf8psflfEZisZs5MZ/wAMilD8iBhcXj5KWqKxRVccWwUrJY8t+43Le/jgzSekajqVVJa2Nww9iqhKj52t+GJFQd5xp+knd5d7fEf1x8ihhjkaVIkWRhZmVbFvf44602ZZLmPfSJAer0NQPxUWX5jEkU1LJfsMxQNzCVMJQ/MWB+WCBwYPYRFnOpR6+wJ5IpxCXvbpa55Y58b5pFkWaRxzmkllkRGZVqlARbdb73PQWwoPxbWSzH1Spk3PdSmpi3/MV/1w58XTpoBkxL4epUY9o3SwNGSXYW6t0GNIViqE1wyRyL/iRgR88KNdFXa9NctWsjAOFqQwNjuDZuY922DuUZpL6moRUBXusrDZCBaw5bcjz64qviYvjiS/h5tnmFxTHwxsKbEbLM67VjBWw6ZIz32iHNTyYLfcYZ6bL6OsTVS5hDIR7SMNDD4HDS66mesWOjqDpAgpr9Mbin8sGDQKuwcO1+UYLfljQ0cgOkRW82cD8sE84HEoaJGYt51kC5zQtT9q0EgYPHMltUbDkQehwtZhwg2W5NPUUcsDTJJKJvWfYqI9ZGlrm17+HW2LJWkYOgeRUBYDuqPlc3wPlymnq8jPbxNMsyhiJN76mvex2B35gYWrLTqctzGKW5MRJ4GWCmy+SBe2DMzSnU6sL6itgB3gbKOY6DEqXh6gkr8wkSjKPLTamJYgFmDqTp3N7eWGHhfhilyvJKJ6eDRLPTRvI5JLMWGrr5scbQ5aiZ5W1AXTJ2MKMwG5HfP+owKigFJUcDgy7sRULKZSMcCGYxaopXjAFmWw2Itz6chuPjgj6N1X/wBVOHQJVv8ASkF1Q91bSLsvly5c8bcX5PLlHE1VDedaSV9axsoWOQNsoJG9rm3w88SPR4kVNx/wzSwWZmzWms6gE6FmUnfkBsBz3t8MecqoUrEes1fq5E9lcO/stf40/wDNfGYzh39lr/Gn/mvjMOLgSxzPz1pqLMO0meXKZXlaNtPZFWAO/eGk2HTp0xZfEfDmTUUEKwSU1HP6pAZGdyrhmju17HYiwPL94Dnzq2CYJKrzRU09mHtWDNa/O3X/ALth3hgWpyCSoizCWDNC3ZPlzRKZXQBbg92xU7eHsm+FywxaSeymBXapqHp+xnd4I42RQ8bAqB0NwNyTtviRGABJNXFJpFtNH2jkizIATYEEiwJHXc41kyqHMZD28cqQ6O1JBZiig2F97CxtuQeYxDpYYJ39XpWJdiNTIDostgBuSd78xzt1wqG43CcSAMw5U51KzU1FHXB6dCESNFFk2YrdwLki6gi/L4Yk5tn1M0Zg1wDXFGSkb2jQxkhZApAO+pgR/mPjcLXaZPLXMtXSpTm7MrSSl3JtYjQCRqva3Lwt4d/q82VqeWQ0dUsp7GRQGVrEgoAd+W9r44sR9UjeOsKV6y0cymYzIrkBCZRdTbcG3IEgjrtbrjjJmcQRpXgpGe50iNQDztvq3G/z+OI1RlSBaWKeQyJTLp+qZV7xJYFtR3B8v8JxzWreprGqpIqjtEjCK4dEbUNvZsdQI3HUgX3wPaGNwZbzDyVjumc5VWRo54ir+1pY1cxVkJeLUFBtGAxuOu/Qb9MDs4npc0VHjhU1AXVJMv1YckDpyv43AOIfD1IuYUMgyqKR0jcJVTRDXG17adQf93ZRsdr3wwcVcOS5HkdDm1NPEr1EZEyKLaSHKjYH/CRY+Rw5SpnIHEHuJgXhrJmzfOYcunQLCZBHM1ySu9rDkL38sN8/oqjDXpMzZCOWtP8AqcSMkhof7SU09BHLHHNURghpCQxUkEgdLm/j0w+FCRte2NVAtrWi9Qm/Erl+FOJMoy+trVz+mjpaKNXkaV2IszaRsVI5kdMDoM9zOdR3uHM1TmNLQK//AClG/wDGLE4lNOnCGfpVyTxxSxQRl4ow7D62/IkX5eIxT39nslq6qKFM5qO1kcIqS5a27E2A7rt1wpXqBWtaOUKZdNxMP5jNX5ZP2dfkFdlsoAbSNRAB5bPc/wDNiRScZPAFEGZ1lGORWRWZR5bavHwxcXErF8+neMlVZYyAduajniv/AEmUNfNlmS/RsEskjT1bOsJUMwAhtt16+OCsqhd0AGJa0ByZ2cynaUVeUVFY627aTsr7LZQVbQfDkuC2XZnLQJWSZk8jRXBh00wChQLFQUWxN9wbnAqu4YrpKvhuP6LmaGeipRWN6qSO0dmLamA2aw8drYPZl6OMkp+IK9KSepoYYXnMcUMm40KW2PO2w8fxwuwUDkwylr4irm3Ga59mQklkkaQhIYowh0xqtlA+HP5nDlWejWqpq2KoynN1Q1ZdCrjZhGg7y3OwYg7EW3wIzHI8vpaWulepnq62KDSomkuy7chsATY+dr+eH6l4tyOuq3tTNTvSFz2NTGYpELaQBp6jT4X6WOFzXW4N+IYIIpxcG5vVTrDXqoiYTxrWU89iGW62IUggagNwB78Rct4EMeYQx1tZmVPaYK7GaQhVsfrNd9NgQPng3nHHWRGekkpqf1iGOV6hXSRG1hnJvtuBc8jv0wMq+OJcwDr2VSqMfYChV91jg9IbiTu4g3YAcCNVDJTZYYlq+IzXxSrcK8YYwEAbM6i+97D3YmPnuSLsKtn/AIcD/wCoGK8fOpAAfVZmPLbT/XHMZnUsgUxOW6lQqj8b40KdbYLXiT0txuZD4jpeI88zqSrp6mGmptV4kFQe4Lb2FtjyPzwb4fzGvymiakr9FQm4QRsbDw5+Hxvc+GB5rahvaimP/wCgA/LHP1ma9/Vzfze/+mAkrv8AMubywpcWjcOMEp6aCCPK2JhiSPvS29lQP8PlgbJxbUpUVE6ZZT3m0DvynbSLeXvwsSSSiVUNAWBBOsMSB5H/AMY+NRyMdfqkRPI+1f8ALBTqO0gUYJ9IOay5tX0ck8MERWLTeHlbUT/iO4uTvgd6OVpzx5w4ESV7ZvR6Cx1aPrVO5Fr9bXHvOCXEWQPV5e7pFZ1kErWDG4AsR5C3LzwH9GysvpD4XkjqFAkzWlslrmwlA8P97fGdWa73MOo2iwntXh39lr/Gn/mvjMZw7+y1/jT/AM18Zgy4EsczwgMsWd4KuPsZhHJq1KoC3tsG3t4X5XxKSedW7tKZIy5YzJIdR5g2XpyHO+5wMFbVQfVjKkCaFhaRu87BmNjtsGsPl78EaWtqNLSU0sUVWLPGsgtHKg2DXbmd9/Lxxk1VYG5kbizcz5mElDKlLLl+Yz1sklPJBKA9wjXARXb97VuRt0HO2IM9Pm9JQRUNLDFJV6bt2cgaRQpuTcbdACLnewxIrcqjkozSUsEkdUrlwWDbWNrIeR9m42vvfG2ZZjRZDOsNBTPVoramE7iN4zc2JYC5vufgMQahcqqiSORAX9wXNKlZKWGBo1Gr1lnjkHmFLXLX/wDIwUMUc3ZyygItfIWikUCOSFyoLXJ2XclgOe/W+OWWR0/EMJzDMHeasgc6denSQO8A1rE7fHyx9yZq81Iy2nipa+njleCQLGSJDvvfc9dj12wVmPI7Zv8AzKbCek4PBmdDLJTQwVlZTgWkM6ahzNgbE6b7m3XE7Iq6qpczR+yquyZWWNxTCQKEW11Z+l+h5XtjvCK6StEFLAtDNBqaOSnXd0Qd4FT0BQkX35jDPk0+b140Cep7Zn0p3wRJy325Xvywaku/NoT6cQvl9bDl1DF6zCtMaqlZgY0C9tOJbAEAWJ678rDxw3vlNJX+jiKGppO1rJIZqqRWUi7Ad0XBB2Nxa+BC5BWRxrHM8ExTU6NUKdYbunRcCysO61vC5G+LFp6F09HVKl7ns6hiWO7NY77g88PMeMyoERoKOOm4uo6enjEccc8YVVXb2Qfzvh27AgeyfkcLM0ccHFiTu1kgmQub8rINsOfq8g6/IDBlPEAw5g+QU6001LV5TJmVPUhQyRyIhTSbg98EG9/wwCqeGOHZq+lrY+Hs7pZKV1kVKespQrkMGGoFd+XlhjrqmPLoVnqJAsbMF1XFt8SRBIQO8RcAjf8A6YG9NWO4iXSqyjapnOtnGZ1LVnq0lMHAHZyMNS2FuYuPxwvcX8PRZ8mVxdrHHNTNOLTUlTICZAt9DRIVvZDscNAVYV1yyhVG1y3O/IDzOEPPeKqo5jMIiAtFVKYdzuNLb/kMX27xtEGW28xgr8sglzrI6qOcLR0Apu21UNUHPZoymw7O1u91wtZ5nKycX59LCFL01NXmKzElgUJ9kWIO/j8sO8+e0cVRTQtIV7alNWWB2VdgPmTiJxBBluZDMqRYjHVHLJImrCgGkEJdQbb8yffhTUUCyi0Yptc2lLS51DSSUlCkWid1kaV31WYG1xY79Lb9bb4Yc14noaqspamQGKKCRI5HjZdJGksDrJIFjYG/hba4woZnk5Wsgm0yK6yMyv26hEFyACxsN9W48zt4i6+nqaWthcMZoZWaJ2iZCvO+gHkeW3X3WxjKgc2BhSSI4yz0GYVVMKGEGGWrjF45Qy27QXI2Fv8Ah2NtsOy0EIHIYpzJcukzHijL6lUmpoUzGOokE8dg7mUFRq23I8BYHy3xeKjYbg+d8bOi04pqbxdyZE9UQclGNfVVB2AxNNvAY1K2w7YSLmQzTC3T5Y19VBOwHyxNsNthjdY7nYWxFhO3GDvUzq5C/uxv6oxQ8lsLk2OJ/Y3OPjxgQy/7jfliCJwJghoVkS+q6n/62/piNw/w1r444ZnplgSKlzCN3VUKtbWCLG3jzGDNLHppYwG090fu3wW4aivxDlhLarVcZ5W/eGKFAZO4y4OHf2Wv8af+a+MxnDv7LX+NP/NfGYGuBCHM8WV2YGhy/LpGIp6sN26RQw2EovtLI5PtXLgW5ADAiaJeIswgWKjWlcyXLrdgouSo3GygXB26dMXBkXo6rWZIs2yiVVhUNTywUrLpbYkHU7athbe1r336Ds/4Iy3hiW0Obz+tyRO0rTIquiki1zq0kc7jntcdcIVqtKxK9IYUGXkxIzaio6VKcGtqaOnlbXEFAPcUDWl/3LHTbbkdr4DLDRK9THVmeskeI6t7EFvYZWP+a/Tlh/yHL0rKxKKvYNLKpljlipi/dAOlk3FxfUt/EXxIgyum9WeKuczQvAssuqwLJpC6rg3JuNhbax3xmPX28nrCmmL4AB7RApsnoo/WJZQyoFiSSZm+oDMtgEAAvzvc/jgtRcLxpJNLk0stLLVyBdUY1d1C11U8jewJO3sAYbSmV0Qjonnip5UgYdy5BAHfcje5I3tfm1r9cc8prnpqmmq1BnWpqlRJ1OpphY6lCKACDY7XFrX3tjmcluDxCNQsYtUOVDLlklqpp0SOySyspbu7hrldwpa9+hN98XBwdUcM5rJltHTVsVVmkC9vHLTwmMsFW92JG5ANvh54g8O8K19dRyUtTlNJJDPqTtJXkEkI1dL3VvHp4b4n8RZ8OCI6fL6vLg0ZhGqopmCm4UbleVybki4+OHdPXIPrKHTEcmMub1OVw0tRl+uBJhpdVZdEd9jcNa19PxwuVvG8cFFT5VRRVRjSI2kgdZI9zyJtvf3bXwn1/GqZzlVNNDmAMkbaZomj53B5EjAaH1KI6FaIAv7JQ7m3iDhtNzctKPtUWWF4a1swz6CKQNTq9Ysr6++Qt+tufTB6fiael4h19r2lKi62BU9+z6th06D54SYYZKfMoZohBHFf9xySW6bHzx2qayMyF5qlA5JuC/4fjjUpbWEzaoYGMNXn0+c0lDlsjA0143k2uwYFtr+AFsMfD3ENQeFs0lmYzT5fHcSXtrLXC29wAxXkUoMUnZQSy6gFGiJj+Nhiaz57LJUw00UgoZhG/YyBY++BzLAnYEttbEO9McXk00fNo7V/EdMeEKCZDLURSTGnlLOVlVgrFTcddWn54r7Mapp6uad4v9oqOdA/fIs23vF8TZMmzWqy2Gjmnp6WOGpao7rmQElQtrWA6c/PECogyaidjX8RjWNjHCEU/wCpxVaqr9IhGpM2Z94qz+SM0UqUU9b9RS0IjiexRey3Y2Vtrj8ccM3zGtip6+tpaicVqlxEjTFwzDb2bgEEdLYh1nFPCkJCmOtzJwAg1k2NtgNyPyxrT8Y1byCHJ+G6WlZgdLzMNWwv4DoPHHeYx+2V2ouW5k3huSPPMscZtllD6ysllC0TRGwA3AU259fLHU8N5BEXSan0SNIXuZXUhiR1J92Fmv4o4srHEclbJSI6llMEa2ttyOJMOfZ9TxAzZ0CVJR0kS9iNyL7gmx8sKpSQNcAQjV0YWBhc8L5LlFdR5pTNMJEroAA1T3CS43I6254eQ88dUy3lkiWASHQpbfUw6A9BiuKniRykQiWCveR9BQwRg/NW3+WPkObhnZZOHFSTxiun4i+GAbYEoAGw0tEhyBdgPeB/QY+9nLa4KH/8/wCjYrX+2dLRTCKWfNKGS19AqS+3xI/LBGHjWKcgJnFQCN/rIlP+hviTUAzLbCcR5s6C7IpJ8Cw/0OPomtsYre5/6gYUouJ5XOlc3pHba/bQqpHyK4nw8UV8ifVUmXVgG2qKeRBf5Pjt6zihGYxrKL27Nvgy/wBcfKjT6rOwR/8AZsSSu3LyvgGOLJ7hJspkUk7GKpVyfcGRfzx9quIqb6MqDAlTPM8WlUMARieXMMQcQXEjbDdPHpp0UabBeowR4eGjP8s2H/yoxsP8wwAp88y9kVS+l7AWZCN/ljrlPEMCcU5FSUjBnlzCBHO1lGsXFvO+Ku4UXnbeZeXDv7LX+NP/ADXxmM4d/Za/xp/5r4zFFwIQ5iOOK8vek19oEk03MVruD4WGKe4myys4q4gnrK2ply6hVFWKlgj7WWYAkszgbKDfxvtyxrDnqzQxwevVlQdF7UkzWvc8+zG+JUWXiqSR0oKuzc+1iY6+oBMjDqTjIPmOJshaCG5MXqvh2hgZKmDifMSGAUokyhQotuASCADc+P43Y4+A+Hqs9jHnN5JFVG9uRnAINjawO4vbcC5t5aZdkOYZU06OuXQK79pBJPP3lBvsQikG3+8NjiWaMUxEk2a063I0xwUzWXwsWck4qdK5HJtLCtSIsFvJGbJS8HwU1DNQmenhUETyKLlrEWbr1tseW2AtFmFdDUz5hTxUsBlVkJJ7pQ2tuATbYbXxLMeXoCXqMznP7zSSdktj07gBt5YjPW8K5WpSopcpCp7PauJCnl3/AOmJpaRBk3lqlR7XCTrlnEtbQTFfpMVCtcmKGRmI/wB03JHuwYheCrhEpSRA1wVljYfnzwtVHpQyXL0CRmTQOSwIbfC+lenjgLmPpWnMbvRZbIIV5vI1ifA90Ef8+H0oAYEz6tX87D97x7+jsvERUUNMQf8A6UH42xxekoLkerQaeuw2+WK0l4w4ozZS9JTOkfMyCH2fHclh+OJtTwJxvmGXRVtXU66WdGkBesuFUA80vpHI4OKJGeIqdTT6G/7Rursw4eoQFqZKIaeUZVGI+GBFTx9w5QKwp4ZJLb6YorA/PSMJNNw7KtX6i2pmD6CqCwZuoAGxOCNHwvrqzTRwAPa41Lcn3YZTTX5MRqeIKML/AJhCb0nVFR3cuyrT4NK1/wAB/XHIZ3xjm9FUVcUkdNBTW7UrYEathYbnEteHZ6GGmrGjZ45NAsObMRuAPgPnh2yXhhKHKGpJkTt5RaUqNibmx/HFzSVYJdY7G2JXlVwhnlXlkFbW5lV1Uk2smJiSI0VdVySevhbAmXI0pZpYihYx9whjfvDntyxdE+T01VQxUknejjKkat76QbX+d8K/9mLZnaVVRJJ2k1tt3b33v78WpgDIi9eo563gpeDpJsw9Xp1EaeqQzjuAC9kU/IknDdDwDBkkuYV0kUNkj0xTP3iNciox35HSTb34O09qupiipKdp5ZT2YKWRBtq9s7W26X6bYFVS1+fZRmCM7du7FUD7AIJ1sLDyFgevxwOqx6Q1NQM8mL8/D7R9hcGpVJUgiC7tJd9vfe+OuZ8OVtZVdqcpIjjcAU7aSzErytba9h1xx4cp8xg4hy6lL6Y/XImv+6jK17m/Llh/qeJ8ty2ukkjmWocwojxA73DG4J5D8zcYTdCrWELTCOpJ4lLU3D9ZT5jFPJSqsdMS0isOSm6hiD5m2DTSs6hQQABba4v8sNGcZrRZrQyO1OkVQUSIWQXK3BG/wvheehiCM3akHoLbHDdEGx3QLMtPhTIjvHo0NTB/eFcfI2/PHEUGWMytNS04YXsfVihv712xIMLAWI+W+IVRmMWXSjtoK1lI2kii1oD4GxuD8MXdtg3WhqP6rbAYArczy6iE8VPEsSl9CoCQyWPPyUnp4Ae7EDKswXO2c0/aU8CD68xSaZdR2W3T4e847TxwVOfLmSZjSTKsvatA6FXI8Lcz8sbZlmLwZrC8VPCiyGONoo0CsLlt9XiLC/TfGYu58zaalTofMpvLA4byyrlYM7yimhspV3Ldq/UHxA64YHisd6OI36jaxxGy/PaF6ZIoIHijRQFBG1vMjr1viatbBKpPaDfqcMIlhFKtXe18QfUyRLLFH6sokkfSG38CeXwwq8MTvT+l/I6aVWK/S8PsydSykbHfYlcNZaKTMYbOgKK7gkXsbWB/HC1wjH636XcoaZtc75jAzhraQ0bIAAbbnSNvdgOpsAN0He5nrLh39lr/ABp/5r4zGcO/stf40/8ANfGYOuBLHMpKJ2WPuRgW5ACwGPqVF2N5LNaw3/K2Ba1rOp0vKQOdhYY19eg5vr2/y33wHcZcKOsIZklTJSh6ePU0YLjSLE+I+OFSeqepQsy2IOqMk3sffhohnlliDLrQcwSQLfI4AZpSrBVMEdSjd4BAbA33A8d/zwvqVuA01PDK1mNM9ZxWbt4g8khuBYg7EH+uANfldNRx1VRS0SyrO2qoi0i0g6nxv18tyN8E2bsJDKtip2YH8/8AT4+WJF2IGgKB0J6YXo1TSfcs0tRplrIUb/yKUFJk2T0wzGmyd8xikbSNBBaAn/GTdrX9/S+D2XV9VW5ZJUCkoKKnW0ZWosxa/Pblb34HZjTT8PVZzSjJajkJ9chQXEf+cDw8bcueBOYcNUNSzZzSy1dRSsA5po2LlepKi99P5dMb6OKih0xPG6qg1Byjj/u8dJWyg5VoeqbRTRduIobuSS4Bt0O1tj4YdVqoZuEcvjiQpIlGzaGQgMratI8L2+V8K+Y13D0dE/8AZn1SseJF1xi5ZQGuVBHMgEXN7Yl5jX5n/ZvLKCqqII1qACOxO5iJBF7cv8PuvjmUtbiLBxTvcyLmGSq3ELdghVkqi/cIBAuSdxg7S5LS0dQZQgeRgAXYXtuf64ixssXE0ixMAjTSLaxsdj/Trg0XQWINhyAAwZsARNQDczk9NHLpQxqVjbugDblb/XHyoqaai0vVTqgfYA+0fIDmfcMQuIppKfLBXNVTpAJVhaOCyvIzXO7H2VsOgvgJw3nFXPndI1JSQ0dGkqGTQLlhew1u92Y79MV6Xli/zWMbI2XQHWNhrAIVxpIv5Hlhe4neSHMaCOGMyMVScu4DBLsVNgdlHd8OfXDDmc4SvqwTt2rA/PCXxxO0lTSkSWjNMvI2v3n6dT5eeIXmWqGwjWa0P6SFhlqbntisalSdIMC7Ha22nkPHC9ludSUbVnY1TNIzKYoqhbWIlBPiOX5YGSZnV5hxHJm+WwyUrvJqjkfvdnZQt/8AvxxyiylI5u2nmedx/i8fHxxdaW7iUbUEcwjVVNTLUyzzi00r9oxACi97325Y3goEVC2tXc7ggbE3v8fecfBUFm+sA38sdY5RqIBtbpzwwulQZgDqGaAs0pM31khVMKd4CEG3xPMn34Givni7ssbAjbfFgUhYndLgi2oHb8cRKrIaWqIKpGh+IX8MDakb2WXDArcnmJq5qvU2x3irEddV2O56eQxMznJ6WlFrRAkWDBb392F7MYNkEMkkZQGzRuQfjhGvXFP5es1/DtDUrnzMD/clZ7WQpl3aWj1hlu5UXHPr0ws5flUuaVUFdKZECOrIvkARc+e/5YcqXgupr8siXNqibU79qIyApA/dDbfHy288Eqbhtae+kl1HXkcCJ3ciMkbSQYPpRLFYBAb9eVsEkllUAdPIXxIXLgvPUCPE4yWHsluRYcr4OtoIkzlljrNmVQGGrTEDuNrk/wDTAPhXMkh9L2SU7QiEz5vFutiVfWg262IVgdv3sMeSw6mrJdTC5RB15A3wsZHb/wBccpi7pZc0opEYABhqKar73tsQffhbUoGAvLJ/eeveHf2Wv8af+a+MxnDv7LX+NP8AzXxmLLgQhzPNMFbIYgiLGoJsWJBPy54I0sMsqFZSg21BSBy8bDfGuXUFKYwTCpOkHffBlKaKCJ+zTQEhaQAGw1X52wDYZfdaRk0xgdoEjBF11NvbztiPnca1lGZVGqaL6wKi8x+8Plv8MEM+/u1FFJGAGIG5F+nniLTzNRzr2IQdo6BiyByQRvuQbYkr0l0qbTuEVJWUg3t4WC7HHyGbQfVyTa11LX5cvw/pjRh/epIt9CO6qL8gGIA+QxyrHYQawbMjAqfA6gPyJxlEWJE9UhugPeTBY3VzfoRsAfhhXzFJeD5zmNCjnKi15Ylv/diTzXroJPuBNuRw0Rd8Atub2+GIULGrlmim78e66TyI8COuGNPXai+4fvFtVpF1CbWz09J3y+ahqqSKqymOCnEjFpGQmzqeZIGw67bc8azTNNPFE0txGUjAJ9hQfLpa+EjIicv4szXK6YmOijmlCQ3uFAGwF98NmdxJTTwGFQheHW1uptzx6MHgEdZ4DWaYISR0PMYMsktnYTUJVVnCtuNx1AO/hzwxSThOZuBz3wi8N1k9Tmcc00heSVnLsQO9yH5YbDI3YNJe7Xtc+F8Q2YCnwJtxFWpFwxrejhqOzrkssm66ijfu335csKmV5rUUeaUmYVr2jjkEvZAqBpHRUHs8j064m8WV9TBllNDHKVjknkdlsNyFWx8uZ+eFajHa1Eesk6mIO+OtbM6oTeM2bcV1+b1s4oR2QkkYq4UamB268sdqPhFEKy5pK8zNuUWTcnwLA3H/AJwbfLqXLRCtJCsPaIpcre7bX588cLkOoGwNr4rmEC35bmQdMNMTFCBdbiw589vfjhLGy2IYkW3uORxtASa6e/RiB88dHHfKb2D7YdU7eBFiN2YPaZluOmMFWQw3FvHGVI0yso5XOI5N7A8sFBvBBebQiK5gukMbn8sZNmKRRlrna1gG5nwwJDMouCQSMR5nZ5X1G+kbeWE9bqDSWy5mv4XoRqKh3HhfedKutkqXLPuTy8sFeFciWvl9fqo1NPGbRI9rSuOZI8B+fuwvDvyxIxOl5FQjyLKD+BOLMaNKRVigURxxsUVRyAFrDGHTBqHe09NrKvkoKVMWE7vCsgsSPEBhf8+WIc8LxMmiF3Q7EpZtPvHO3uvggpLgat9saPEgJAHQYbtMm8hvTRuN2UN4cr/PECqiSPUrHSR/hNif9DgnUKPWFiO6NzB3/wDGAHFa+o0TrAzqFS4uxa3zxfEr1mZWjmhnliJJeV9JXu6veOX4YVOHqGXMvTVktdS3Ro8xojOHXSwAZAb2O/skDa2GfJZHOQwsWJJDE/fI/LC/6P8A+7+mGKOPZRmNMo6m3bLtc79ThfUkpT3L3E5DyZ644d/Za/xp/wCa+MxnDv7LX+NP/NfGYuuBCHM//9k=" alt="Property photo 14"></div>
                    <div class="photo"><img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBAUEBAYFBQUGBgYHCQ4JCQgICRINDQoOFRIWFhUSFBQXGiEcFxgfGRQUHScdHyIjJSUlFhwpLCgkKyEkJST/2wBDAQYGBgkICREJCREkGBQYJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCQkJCT/wAARCACbAPoDASIAAhEBAxEB/8QAHAAAAgIDAQEAAAAAAAAAAAAABQYEBwIDCAEA/8QAVRAAAgEDAgMEBgYGAwoLCQAAAQIDBAURACEGEjEHE0FRFBUiYXGBIzJVkZLTQmKhscHRF0PSCCQlM0RSgpOywhY0NVNUcoOi4ePwJidFZXN0lJWj/8QAGgEAAgMBAQAAAAAAAAAAAAAAAgMBBAUABv/EADQRAAICAQIEBQMCAwkAAAAAAAABAhEDBCEFEjGhEyJBQmEVUWIUkSMy8DNDUlNjgdHh8f/aAAwDAQACEQMRAD8A6X9bVn2Bc/x0/wCbr71tWfYFz/HT/m65g7QwV7Qb8fA1j/v0Liby6HWmuFtxUufr8Ge+IeZrl6fJ1n62rPsC5/jp/wA3X3ras+wLn+On/N1ybLBkZxqLJEU20S4Vfv7EPiNezudeetqz7Auf46f83X3ras+wLn+On/N1x7IOmBrHumBXqM6P6P8A6nYB8T/Dudietqz7Auf46f8AN1962rPsC5/jp/zdcv2alAjXIGTovBBDUxK3LyE5GCN8g4P7RpE+Hcrrn7D461tfy9zon1tWfYFz/HT/AJuvvW1Z9gXP8dP+brnGstsSJksF3AB+Jx+8gfPQ3ujHJnmBU9PPXR4cn7+xL1jXt7nUHras+wLn+On/ADdfetqz7Auf46f83XL8oC/PpofLJjKk9NGuFN+/sA9f+Pc6w9bVn2Bc/wAdP+br71tWfYFz/HT/AJuuQaiJJY+aLdgN1z11uihSZDHnYjrncaZ9HdX4nYD6l+Hc649bVn2Bc/x0/wCbr71tWfYFz/HT/m65VoofRYXlkUjG2x0Iqqk1NRI7nbpoY8Ibdc/Y6XEuVW4dzsH1tWfYFz/HT/m6+9bVn2Bc/wAdP+brjJuTl6knWLEAYAzpn0V/5nYX9V/Dudn+tqz7Auf46f8AN1962rPsC5/jp/zdcXkllxjUyjdS6mpi7xE35Tnl+4eOhfB2v7zsEuKX7O52J62rPsC5/jp/zdfetqz7Auf46f8AN1zVYzSQQF6KnjD4LM8h3yenw0Vt1cKqRjInKSvLkdCNV5cOa9/Ysx1ifWJ0B62rPsC5/jp/zdfetqz7Auf46f8AN1z/AAQiseRSpWKCTKEH6xG/8dQxbqqvuEtTVMgo1b2V6lsHQrQX7+xL1Ve06M9bVn2Bc/x0/wCbr71tWfYFz/HT/m65/UvUxstLgKCRzHx1KigjghAOAwHtEefx1D0Ne7sF+p/Evb1tWfYFz/HT/m6+9bVn2Bc/x0/5uqF5lb2VGo1SEjUkbDqTrlofy7HPU/B0H62rPsC5/jp/zdfetqz7Auf46f8AN1y9WRSVBxzFVY9OhxrzugpCIqeHlvo/p359hb1v49zp2e/z0wQy2O5qJHWNfagOWJwB/jdbfW1Z9gXP8dP+bqrOyQMLdWcw39Z0v8dc+cWPEnFV5X0ycYrpxjlBx9I3v1Qy43CbhfQtQnzRUqHntFPLx9feboax/wB+hMEExAKpI6+DKuRpy4+uvBUPF95SuopnrEq3WUIXBJxnnx3wGPfgfDUO3cb8IW2ljipIZDFuzJnm5Tn9LMpxn462PqEVBRitzKWjk5uTewHiglI5Xik/AdYVFukIBVCR8NMY4/4YjJkaASlwDzcowrf5pzJsdht0Osm7Q+F1Kp6OioccrxhVU7DOMnqOh+el/Umn0HLRRfqKq2eeUBhF4+LKP3ka00FvqJ3aneBo5kzlWZRkA9QScEbjx07jtG4WSblaNkXm9mUtFysM458lCSPlnUWo7SrEJ/70pZqeeVAjk1EKnOMrgd0fDYMM9dQ+Jy60EtBDo2ClpauihDsg5F2P0qH4fpees4hcBVh6donVxz7So3KeX3E9Rjb3HUqp7S7U9CLNFDUiGLOUepiaaY5+oxMWc5Y/cNKVRxZiEKoqmYOGWaCdELIOgICdQAAf/HSMnEG/Qs49HFKrDlZLc56eVmSERyD6Mq4GxbJPXHmdDo5qsSzU5wZFLs+VOynLAjz9ogbZ31NTtOhjp+6MdSYkiMZ/vkADHMdjyYBwwHv0HbjJoa/1tJLOVkPtutRjvhg+zzBdskkY8wCNL/WyTtDP00aphmWlrUhghmjXv5mCxoCSXBIGenv305cEcFS3Siq/TqialMUq8gEKsrBl3IEqHO48tKMfagjUzNHBKFYyoXarfMTt0wSD0BBxsM50SsnazQ22mi7ylnSLlIjDVz4fGFJ2T9UY+J89HLiEpx5RS0sIvmHj+jGk7zm9ZL7TYHNbaJsf/wAf46VuPeDJrU1sgoFinkkR5XZKJISACqgZiQeZ6+Wt8vbPbKZQ0lPICYllXNU5ypAOw5D/AOhrKo7XrXFXeiy07c6yiLJm5iASOmYz1yDjOox6mUZKSZM8MJR5aE+SzXyMMq06urDxcDH4saGHhe7jJakTHuqIv4vp7i7Yrc8ohNOVckrymZWIxnwMWPDW6m7XrNOwV6dSeRnwssROyk/818s6tris16Iqy4fCXqyuZOHrlCpZ6QhR1bvYzj7mOsPU1cRn0SY+eEzqxqvtJtNzQ01voYmqVCzgTCKWMhWBwwVVOMkeOpCcQcSyRLI9r7O5ldQwBKIQPeC22mR4rN+0W+Gw+5WsNoqlfElFVDPj3LEfu1MW31MjpElLIgOTzGFhk+8YwNP4vl7O54U4El98dTEP97XqXG5EktwLw1Jnr3d1jA+7m1z4lJ9YnLQwXRijTx1sSyQyUMxUpyoFjwM+J/bqbRrJS0qYgZJ1xzooJz89/DTGtwqRu3ZrbW/+ldU/nrM3OXA/92EinpmKvB0qWtb9o2OmS9QbFWRiLlVeTH6IGotdVytGYokcs22w0dFzjzmTs1vGf1KnP7tevcLYPan7Ob7FnYFqphn3Dz0taqusRjw30YDsatTlo3KovRd9s6IzQvzcrH2T4+et3ptiYAngK+/KqJ/jr31hY19peBeJOZehWZiR+3UPVpu6OWBxRFWnC5OdtD6t1LFAQfd56NtdrOw34K4xHwkJ/jqLJPZazvIoOHeJaCoC8yy1k4CA+G3Vt/LRwzpvoBPE6F+SBlzMdyfPwGtUVKxcSBlUZ+seujghiKlTGzfHpqLKno/WQOw8MbDVxSsqSjQ/9mUYS2z4PW5Uv7zrnrijg+eo4lu0wqogJK2Zv8bH4uTroLsuk7y3VJ5cf4SpfnudUxxCwF/uQNttzH0qXLNGCT7Z3O3XWLqXWaRp4P7KIS7ULbTVXH14mmhDSR1UgSQbMm/gdQqHs4pHprYFcr6ctSeYRbMoSE46frN92jHaMCeOb3/92+mO2DlXhGPyoKyQ7+ZiGtXVYoeFCVb/APRn6WcvElFvYWIOyuB4+4LFA7AGNowoODtk/u1PHY+JIwCrsFbnCiLABO2dlx0090pIrIOVmB71Nwf1hpZ40vNwi4zu9KvFFVbIYpAY4+Zipz16HWZJI0osH/0OFihMcrcoIH0J2B3OBy+eop7MKeeolgUVINOwhkdoQFBCqQozjIw3UdMY0RtNddDebYr8ZT1UUtVEvdc7+2Cw26+OinFNxu9HxJdaiCRzElTII4+cHvGVmztjwAOB448xoa9GFb9BGreDoaZ6+F6a4LU04JcpAx+kB9ljkZ5W2AOf269vHBBp7RS1a01bG/MVMclOwCkLzP1wcAcwBP6Qx46PccJXX+uhu9JHWJzwolVDiflVVQAp9UKSDnf3+8aHy1Hqqit9X6a0ktVFKKmIc5VNlCuS2+N9wCd/idLaTHdNwLFwr6wijkhp5XbvJC3dxmRVPKWU5B6Mg+RzrfQ8Dy1cFRzQ1BeId5GvdMMsSFBGT57beJOsr0fQbZX08cvMCsaju1OVZQG395Ddff7tApL5NBF30LAEpnAAypIfz8Om2hVWS22g3XcIR2xp5PRa6WOF0Xu1gPM6E4yN+uQw8vZ002fsxoquCklFWsXelklDkfRnOF6N0Y7DOM+Gl7h7030W41JlzHHUQRhlUsUPMGG6+/J3HVhpr4KrqqiAnlkj7x6l0cqAQ8SgGNNz1Ll+nT7tMjtuhct9gqOxMCUhZ1aEoCX5t+bPTGcYx46gXjssisdM9RUhGiMipGVc5YkEkkcwx01cVlE8lJ3k+Rz45Iyc8igYGff56Cdox/wJTZIINUufwtoozd7gNVuVhS9mcJgR2oa9UlVXQLAzeyRnxb36+/ouDQjFDcA3KRyijcj4Zz00R4/rJqO0cMvDLySS0jq0nNvgchHXOcZOPjpGh4nvs10paGGvmiE0qRCTbByQPL36NWwW16kriXhtOCRBWmkqw9WxpsNStGEBw3MTjzCj56ebKXFkty5O1LEPH/MGpPaLani4bttsrZWnaK3BpW5shplKsX+PMudRLM2bLQH2f+Lp+7U4wMjJh9/7d9ayEzuAfiBrMsPAA/A6xLE+H7dOF2YGOI/1SH/Rz/DWBhgH9TEP+zGthznwOvD/ANUa4g1mCn/5qH8A0V4UtdJX3pIXUp9DIA0fskZx9/QaG7noNHOCAV4ijyP6pxoJ9Ao9SpP6UL6qjngoCSPMj+Gn+zTvxDwDHear2ah6oIyQnChQcY6e4HVRTQ3JWYdy/UjDUEZ8ffHq4ODVZuypudSHFUrFTGEweYfohRgfLSvVBsj9yMYzJ/rD/PQ64qElCgtgKCAzZ/foqN/AaFXZfp1yP0P4nVvGtyvNugY4OMrkufHOoU8Upl+tjbpog3IviPlrwussDLsZF3wOp1cWxWluO3ZjF3dtn9nGblS/vOqp4itlwn4gucsVPeDG9XMykLsQXOPHVtdm0veWyXbGLjTbfM65s4pra1eJ7uq1c6qK2cACJth3h8pNYuqf8aVmpp4c2JUWR2hp/wC3F7Of8qfR6hytdwumfq2Wob75Yx/DULjyy3Op4yvEsNtrpY2qXKukDsp38CBqbErxXuyRSKyNFYXDKwwVJqACCPlrV1Uk8UEn/VGZpotZZNjJRN/hCmG5zNGOv6w1XvGdwml4x4jxFTv3UropMLOSMrucOMasG3MBcKZuYYEqHqB0OdLVx4FkuPEN4urXWnRLg5xB9E3IOYHr3wz08hrNk99jSSFXgy6VsvFNohkjpFV6yIYWJs/WHm51ZF8sEFzuNZUtlnE8692zey30r4+BH79BrRwE9BxTR3U3OEpDUxydwqwrkA9M99t9x+GjlZfqCjEtTLOqK9ROQD1P0jk4+QJ+WuTvqE+iEniSirKCsS500aSU1ShUq+xDbEoVH6Xs9R1x4ddB1Sn7qippI+7pnLPGSpZUY47wAkf53Tyxjwzp+uTxFIJxNDPQVfLK6M/1jjOeUYxlcg/I6T7vTGivtRR0bsIAA7RvIMqrgEsDjoGwPPHXxOgcRidoFVlEq26Sip6YNVicQSkt7DAAMGGMbkAdeulpu9C1FLJDE/ch+cFThQAObGG3zgn5DTq00M9M9OjFpYahZI1yqGQmIKCCev1c4+Olq4wisg9ZRSyRJVNJE8alNipJORnOegyOuTpUhi2Qz8HzF+G7tNUNGyxVlKqxMrBeYqCcnPQe87+A0Z4TioIbY10kqkiaSqad4jGSYo+8xGzHqE5lGPh8dLdhgp6Gw1QimEz1lVEQp5WCAjJyAcj6uMjHljTVYGhWlpIIKX0inlkaklJUxgkZdcuRnbn8D+/TIu0BLZlzcPMiUKQtVSVMhAkZnJJHh8PD9uhHaOf8C0pGNqpP9l9TuDmpnsVM1PN3hcBpGJ9oMRsCP0dsbeGh3aOwFkpsEjmq0xt+q+oT8wD6Fe9oNQY7fw4jRQTJ6EAFlVsjOMkEMDvgbe7SFaLtG/EFuiFltwZa2JVZXmBDc43AMmM7eOrK4ktMF/o7NE881NJT0sZXDRDmyOuHddL9p7MRQXajrxdGkWmqEqGjxTAvysDjIn26eWnxaXUU09qH3tGEppKb0r/GmgctsBvnpgEjPw0FsefUdAcnHo6aK8eXOO6RJIEMRFHICjMreJHVSR4aCWaqpUs1Cj1lKrCFQQ06Ag/frsdI7IrCDt7z/wCvnrAkHx/drH0yi8K6jPwqE/nr4VNKelZS/wCvX+emcyFUzL4HX22sfSKb/plL/r1/nr7vqc9Kql/1yfz1Noime48xrRNEsy8vPKmDnMUjRn71IOtnewHpUU5+Eqfz16GjPSWH/Wr/AD12xNMjeikdKu4/Kun/ALeskjZWBarrnAz7MlXK6n/RZiDreFDdGjPwcHXvdH4/AjUUjrZiuPDH36XuJpZI6uAKcKYyTg+OdMfIdgT1OMdc6XOMIXWemO4BRhj4EasaankpiNRahaArVJ5TztztnAKdPv1rNUVk5xgMP0vPXndEjfXhh+OtdQVGW8jLQ7Lan0m2TsRgi5UoP3nVW33/AIHte7g0s1ekhqZSyqowDznIG/TVndlCclrqR/8AMqX951zXxbIy8VXlYxVcgrpwuH8O8b368rxJVnkl/Wx6DRebDFs6K4utF1rOJ7s1LUV7JISsaqZFVHxIM5DYAy0fQfonUN6CrrOMJ+5SlPotAsCo1V7R5pTIxJ5TuC2PmD46mcXdovC9s4judJV2CqrZqeciVhHEykg+9s/s0DHdtxHHWIpj5rQsh5Tg+3USHfHU8qKM/qgaOEub1CaS3oaI7Pd1YMkNKpG4IrSCPuj1n6iu5G8NEc5zmpY5+P0fXQpWlI6VJ+CSfy1mA5H1Kg/6En8tF/ucmvsT5uF7xLR+jU/odIVAEckdS+UK4x/V7jbQKSnvbtPT1k9E9X6QVEvO6sgLHC+0pyDnqd+vXGiSB8fUnH+hJ/LUavo4GHO0NSC5bLRwM4DeZBHXbr4aivklME2613Cz1dbJIsEtHMn0gMrgU5OR/mZxg48tCr9SV4pzVU9PTRJ0dYmYFQfM8uw8cEeI330UlT0OqSPu6hojEVmKwuGMZbq2R8eu/wAfCBBU2oTyANI9rqlHdzxbKr4xzE9OXbDDfGx6ZwLDsC0tvq5xBK0sZqk5lUGR/o3Bzg5G2xBHhg/LUWey1NZY3qZxGoFfggq2VHNk7+/DfH56EcYUctrkaESO1QqhC3Ny8247s7ddhyk/q+/UK4QVme/MdVS07nuxzg45lbLYO3n19+dVsmRQux8Y2kxpudE9oxRVdxo43pixj5kaNnYKDhTjqOnj1Om20Vg4ft088tTTTcroTUI3MjRgqe7AbxKs2FHlvjVQUUNc+B6RVPA7HJK8ycx2+sfft0/frKllqzGLbPVGeMkyrSrIGUyAE5AwPa3Pkeg31XespbIhRvdnR3ZxxNBV1VRTy3Gn9Jkk5TBlQ0xKjDqAdxkEbZ2AzjU/tJqaiqqbZZKTkWWpE9UZJEfkURKOrLtk8x6/w1UUlxltMVsqDaKCjqaSSNqRllLy+zseb2s8pPMSCB9b563TdodfdFmrY62mp+V3md5pXbuEAwAFHst1bfGdx7sktXS36guF7sbqimk72ngoHprtUVJ+ihpquQ4XAPM2VwqgEH+GdTzwtcKeNWqUogfE+kugJPQDMZ1RltNdWMvqyB3UthaiFiqsw3GQSMg74AxrfVy8Q0NWIGu1RSzxRghGkLHGwLLhthk4+Wix6yUuqoCUV0Rcd9slyjstTzrEY0i5CgqebkUnfClB4E6nWHiNBYreJaGN3WnRSxK74GM/V92qZF04widfSuI600UZVqlfSmPLFkZ5hzHbcD4nTEnHdHT2+CmiutuieOMKCtNNKT78kKAPkdXVNeoqSa6FprxDG+y22JvdlT/u6yN8jH1rSn/d/s6pWs4kW5grV8RI0Z6oiSIrfFQoH79ew3mL1ebet4o1pmm74gPMrFsAZOF6bdOmoeT4DUPkuf1zTHf1NF8+T+zrw3emztZYfuT+zqmY1tf6fENKmD1CysT+zWTPa0L93xNCebH9RKM/s1yyfidyfJcZu1MwybJG3vCof93WJuNEfrcPRH4xp/Z1Sr1FKgZVvlMwPgO/UEfh1GWuhimzHXUHL4MKiRWX5FDqef8AEjk+S73qrW+zcMwN/wBjGf4awMtnHXhal+dPH/LVM+mUzk/4VpM5yPpW/lrwVUnRbrbgoG2bg6/7p1HifBPJ8lzsbNIAG4YpwAQ2Fpk8DnS9x5crbR2pXhs8FBHDmeWRIEUkAYC5Hhk/u1W7TyMMG4Wfm3/+JSYPx+j/AIa8R4pHZKmssnIQQWauaTPxBjGihncHzcoGTCpRacghw/xBT8R9+aaGVFh5QWcghsjOR7taa/iWmpKl6SOkq55xtycnID828PgDqMhpresklvuNpikbGVhqe7DfEldbI7zNAXK3a0czksxkqlZife3Lk6tfUsix1XmKa4fDntvylq9jFRX1NqrHraRaVDcqTukHMTjfOSQM/Ia5+4ogZuJruTLUZNbMfZmgx9c9MnOugOxqoNZRXCqM1JI0lxogRSz94mQCM4/RJ1QfFUMn/Ce8bj/js/SoA/rG8ObWRqZynLml1NfTadtcuJbIZe1rkHaDfsShT6XJzYTJIx56fk9m6p19mz0g646yz/y0j9qZim484gjWljlZauViSOYgj4+Hj89PRXlvEwyP+TaIdf1qg6Rwp+fIvn/kqz2Qz22xJXUkMsdvt9TLKrSM05kZ29ph4MB4alnhJsqvqizO7AnkCykgDHXL48fPXlNeVsXDsVY0ZkCU4JC4z9aZurdPq6jVvaItHw1BfhSSvDLI0XIO6XlwZB9ckL1j6Dpka0Gtw10JY4Qkx/yHaP8AVN+ZqJVcKfShJbBZoymXTmhblm23Gznf3HGsLZ2kJX8OV97SnaJKExq0TGIlublx7QYgD2h11q4S7TIeNhVItKFigg9JIIilBHMB4dOo311HAe8cETCjmqDQUEUUcLnkMH1WK7DPMce4/wAcaX6emoqij7mkpjTxRlY2RZ2jMTHAAZRsfPO+d/mWouJafiiluzRLWKIaUtHDJL3wXmG/KRuu2+fdpbsU9TW1NVCYGM0E3dc3evzopwU8cEkEkgDb3eBKujJdivxXbbpREJW1iSvCV7hAgDSxE5AODuQP9nQi5+t6eqidaufvHnNLzM6g5VFOVVTsCGx16j4nTVxxFJT1tCwaSOswzo00gKZ5uiHrgjm2xnfSdxYjzNIWmhpZEq5JVFS8mWD4GQEUkZK7eYI1TywVjo20CVvt+lo5KEVkrRqpJgqJiF6Y5lGcE4AHuwB4aL1NkqjRTV8no9LBRTsImWIursCCQTjdsEHyxj5ry0rekh3ajkhVT9GqzqyjxIJQeOcZO5OjVRXSQVb0tJHN3bSNMe+lHIcnIBUZGfDfzxqvkpKgJptKwhaLzXyzS8n9+mXdZFIR+ZsHvFJPsnIG46Z1NmeaGP1WYIqc0RdwpI5mlyoZQehBx9+l1nulK0Hd/STeFIGAcDrzZHRunXcjHhtrGmuVfW00OYTLNFzKWkZj3eR7Qw2MdPDfONVnFVzHbVuxlN8ip6L+9KowBqxquNlYtEF2ITkyPM4Pl01nQ9odZbSIqeqWBpDzd/T0gYFwAc+Y8PHfxGlO83OCKq7hklYTxIHVSo5CTn2cAn5dNb5O9oLbFWRSQxqEQd1IrMz9NicZCj2RnffbbRvxF1YMpq9kWtU1FN2gWSGsutVHHV00QaQ+lM7yuSMtyH2VBwMKPL4aE8LcHWjiR7jLVGYimmWJNwHG2/N7OPI7amcLUIuFgjZU9Bq2ApxPDPzQVUhIGGBX2T7sbDx8NH+C4IYDcRDNVOzvE0wm2eOXlw6HG2QRjWppVJJcwrK7TZAbss4eHQ1Xy5f7Ot9n7KuHKu80dNMKp4pJOVl5lGRg+IXTS6nzf8X/AI6k8PpniO27t/j/AD/VOr8uhWj1KK4jks1j4sutkjs7ypRVEkSyPVbsFbAz7J1Da62ZB7didj4ctYf7GjnGFdYZePL4Kjh6skqvS5+8kS5cqsRJgkDuzjJ8NC3qeHc78NV//wCz/wDK1m5Ms1KkzWxYYONtFh2ns44cu/AFBxAaaqgqatwWAmDBBzMvKMr+rn56i/0ZWMjaWsH+kn9nT3YkiPZTajT0zU0JKNHGZe8YKS53blG+5HTUMKcAcr/fq9htwTZm5UlJpCta+y+wz3WkgleseKSUKy86Lkb7ZC51XvEfqy1cT320U1GnLbal4kEspDSLz4GNtyNs9NXnaVPryg6g98BuTqkeKxYKztF4hklku6TPWTiRY44Su0mNiWB6jx0Oa0vK6GYUmt0F+zLh+0cbcSm2V1G8FOtO0/0M25IZFxuPHmJ+WlGeeGOpkg9DhIjdlGWO+Dj+GrN7DqWii4tlq7e1bKI6URyipCJyh5EwRyliTlR5bH5aBpwparjxO9C893bupAG/xQVQxyB1yRliOnjqvFyauxvlToldmfA1p4yo7rPXxSRGkUd2IHxklHbJyPNRobL2fIa2eMVcPcRuVX6ImQjbruBn4asXsdgpYKa8miWpMUpZSZygKlI22AXwxJ5+GlW9XY0N7mhEbOEJcqjAc5wDgnqBv4eR12bI4Qtv1FwSb3LD7GLFTWK0VcdOzN3lzpSxKgdM6pXiavqF4kuwCqoFZMAAy4Htn36vbsorYa20zvEjri5UobI2zv0PQ/LXO/FjzrxTeVFLcGArpwCEOD9I2qs+aaTixryvH/KGO1u7tBx3xNSIsUJFXKe+jIDNknZv3fD7tN78Rmh4juArJualho4eYJE3PGBzEdF3AyfHfO2caDdqXZgl47Q75cBcZou+qncx92rAH3Z8NTrheqCR5KGttcQkrKSOnSZJACJI1PKSTjrnf4AataXDHG5NeovJuiw6o0t44Ut5QCenqBGgLAjmBacHyPQ6VLvJH/QxbRIQQKicuBsQOeXB2+etVLcajhjgxKq1Ba1UmQLHUK3slRO2MA4AOAcAeOpPFcc9B2S2imp4I3Z6ypiPexlsgGdh7+qj7zp0uoS6EPhdoJOzXiNY1HIfRXAUEDGI8HPxzqF2FPFAt3GVUeqCPiSybfs0R4TpCezG8VEkCRyuaaHCBs8imMAZO+Ov36h9jltrBR8QLHTmjl9UtySSIzjIdeoJ8gdAl5WvglbGPZRcoYKXiBeaIzTUSRQxynBaTumYKM7DIU76J0dytUIapkEnpSBHmWIlG5ziM5wQCFz0/f01E7NbJHJQcQpV2/mgNMrl3BJp35DyELg7Ak5P8NR6epiorla5JBDTzRIstTUod5FYhVWUDxZmBxsRt4A5VLK00/Q5p0Bu1hJVqLbUVDh1nkHKrdUAcDBx0+Ok/tMpkglmhWSWPkqiQ3Md0UIEx1PiT92BoxxV6RdaOhq6oCOn74pEzAnvikiqdhnAJ8cfonU+qoKa6XapaoFRTy9+oFLSx8ywxiIe2XIwADy79Nj10vLPmapbhQk4xdiN3ckNEa+apiOABGwQvzBjvzcw3bqf46218jPFBM1P3cryCKRjIwjBGOUkgnDYBBwf36J3Du5qqWheMFoakxPGUVgwwCPZUcvtDKggjB39+vL1V09uo/S5wtUsifQ0oUNGu+Qo81yRvnOx66znPllT3bAb5nuLndyPcIriapZnYKiR08bMDhgpxkAnbO/X36l1V6q7ZBzskNTS8xZ5VZSS7Z35CCDjlYAgY89+kW1UkrWqor05op+eVjTrIY40jwOYBjnGMeByRtqRT3L1y1JN6JUu8PIkqRsiR5LE+ySNui7e850xpN9LrYBLewdDdUNLUSUvIJHkEojMSDu1weflc5IHTx6Z6HGtdxaOveO4U8vdmZRzwfWVCDjAbOeo5vPReleVaxqalpaVlgkeGpjmQssxzs3KCCD4YB92mSzyUs1NJGtitqkH6SNopDzMP9LfbOD/AB1bhDm9Aq3serFeJbhcLdQxTiOPuhGjxjA7uOQAEcuwPsrg4306Q2imtd/u0FGS8TiCXmZyxZmVsnJ88Z0k2K1TVFbZkntzBYp1khel9lBHlSTvvlWUAjwyfZ3zqz62ijo+JLhFBEkcSxU6pGvsgABxsNXsUapAZXasgtD7v26yoqmO010Fwmhnljpm7xkgTnkYAdFUbk79Bqd3YP8AVj8WsGix1RN/1tWGiunW4qNZOBn4grr5Jb+Lp5q15HeKW0c8al25jgFPPUg0fARBHqDiDpjexD+zo+EUjKrGdyM83iNtYlM/oL9+kPTY27Y6OqyLZM0U93s6cM0fDdtpL6vowUJLX0LRKwXPVsYz7Wo3dH/MH7NTwmP0E/Fr3u/1Y/xadGKSpCpTcnbIdDJBQ3Cmq6p4oIIZFkkkkYKqKOpJ0g3Hg23yX2510HFnBJNdXz1KNUckkgRm5gpLIcY9x0+3S40lmjV6wxJ3meQE7MR4aU7rxFzVFFXUzQRj0aXlbPsthxkEeex+OuljcyVl5dgj2d22z8JXyuuFw4u4YeOphijVKSYIFKuGJ5eUKBt4DUD1BbYbnUXKm424bp6qodGLekGRU5eXcAp19kdTjTZarhT3ylWqo1Qo2cZGCMHGt8nLGMyd2u+M58dK8FLYPxLBHZ9HY+E6GthrOLrFVTVErsnczFc80YUD2gN8jVa8Z1KR8QTgKsdRSzBAVLMX5wowuQUGM7n7uurekUjYoo8NCKjgGjuHEVLepEeRXp1eam5EMch5GjyxZuuwOwG4+ek6nC5Y+VP1Jxzt0ff3P9X3lhrKMRlRS3OkBZs5cnmyd/gB8tfXeGVrtWt6LAczyHJkGT7R92mDsx4Pp+EKCohp6momE1wpGAlK4QAsMDlJ2zzddV3fOO+MoL1cIoODqSSJKmVUc1uCyhjg/dpOKLiqY1wc0qQe46qVi4ruoJ/yhtVXxPPz1FIykEqxO/8A1Rp846aa58eXmio5ITKtYI8PIF9pmwBqLw52ZR8RyB7xJOw5GkU0U6MozgAFcZB69WHw1aUlDeQDTk6Q38J3i11lkgoqPhWS8UzHmxUTwZ5hzBiVbp9Yj4aYLhLWvEsUPCVtFKv+T1TrIqtlt1APKMhj4eOi8VO6IEWkptgBtBjBxvnDe7WHoc8jovo0XtHAAgGP2v00lzt2hqQKp5r5HA1NFwxYYYHILQhcIemMqDg9BqAOJlsN1jt8vDdsWaQqJKa2wMrsrZ5SzAY5fE5yMaN0lWayn9JpIUqIWd1WSGIcrFWKkdfAjGg/Etnp69Fet5Qq4HdyySohJ2GQhGfHbOptnMl+sIGpqiK0cNQUTyq0TPTVkKqdscrALgjfxGR7tJVxt0SUMUNbQSwvUf44opRwvfKQp3IPj4jY58NYQTXWzz91b6e6TRwuJOWhokp6IN05nkcsWUDcgnJx10LuXH9WlzS08Q1MTW2WJpZVPdrI7KVwiAKuMk58Ttjx0uc43QyGKUt0B+OLfT0dNYqOCNaeDvnDFJe9B3BJOwJORgjwI038D8OJSi4xyU1NHis54Ux7MccibLnOcDDZXOlztB5qxKaa0iS409GyxywVChGjfvAFYbAklTkqc79Bpu4cp7RxDxDFZr+qSTd0k0EnPiVZuTcfV8UAzkDqRjSfGjzLlob4EuV2JPE1L6uvV49Eiph3VTC8cuFMUP0Iy3LuG6AeOMHx0pz2tfV1PW+g3GsVZebkRspMqnJyT4DfAA+/OrQ7RK22cPXCHhu326Semkp3i7mhphPUIcg5Y8pYr9UYB2yPhpcoeI4YaClsVVDci5ElYDLSSLzHGctnYbKchRgAgddVs8t7SB8Olsivae0UVdcEMUUzQMnL3ZbCRHJLYGMk/H7zo03DtVFSU1sszssSyvMRKmSNxyZHUD658zsD4ZYLUxuNDcquOy1FPcqKHuJ4o4d1ILFZOTI2zv8AeNTYo7hT10ElHQPLKULVFHGved0zMDzH2vq5H1idubGd8arSk7VvuG8NPZCXT2imtFLXQXSqKRyVLO1U+EDz7Ddv0Rnm8R9bVucE8OcPXe3UYavtKXFZQ0fc1KyNKobPKyZ67EE+IxrdaeCL3VXC7T3ezW2rtNS7hqWqZ4+WMsWLk/Vz8vLprTBdbDwta7fVWWy080necsdXOiRtkqTlFHXAyufE60dNk6tCZ4eXeQ+V9t4foJqaaplhgZqgSQyNNssgH1VPhlc7eIG+gdRxBHc+MK2upGaalhpiytEjMrDlGCfLqfu1WtRc62v7z0mdZRztKi1CkquScYPuB1L4TvbUBuaiWGLmCnEP1Gx+jv1zvrTwQbl5innklHyliUfFtLPdZLbKqRzJ3WxB9rI9rB92x0Ro7vQSxzGeZITT1LUrs49lmG+x8cgjVSx3DuLhPUwl075DhmUEqcAjHluBrFLlLLSegSnnjNSagHG4Ygg492r7wFFZWNHD/Fa01xqkrZx6CDMYnxgMSWb9vLj7tWDHTxyxrIhX2lDDxO4zqoQIFt/q943mkDcwHTlABwfvOrD4HuU54fuVbcKlR6PyqHyuQViGw8+o20rJBLoOxu+pOgraOqrpaKGZWniBLAD348ta75XJYqNayZohF3oR2eN2CqQxz7O/UDSLwrxlNbrynp0aSLM7K8wADYZgQfl00z8a8Qz8O3W1V9PUSNBPF7cJ2yD03+/QyxyToOMovcSeOame6WqkqiUkgernVZkB5QMgqv3b6WZqgzUNNGzDMIdFwuMA+e2jclfNdIK2CoDkzVBrFC55UfDZAHvyPu0ChMppy9QuCrMPZB3H89WYeVUytNW7Q79mFxmluc9vUqxn5SinbAG7EfEai8bcRSrxE9LTSPFTRurMBg5IyD8saH9nt2jjvMVbEZkWWGZELKVIPKcEg74yNBa2VGaaqqu8IAklcrudlLHGfh+3QOCtsJN1RY1VxtTxXeWIVEZp3jX0dihw+fl8tSeIr/U00jQUkgWIQpTuxIUoyykk5PmOnx1Slk4ytfEd0p7dHHdI2ZWKtIU5V5VLeBz4aI3Fae43KZJHnMpUSENK+46A9fdpahGW8WHzyg90Xr2ZVMlVb5mknWYivowSrhsHJyNvfnVWX/h26zX24yittah6qVgpo3JGXO2fHTx2E0UdFZ68RqRzXSkJyxPn56Tb9xRcor5cUS50iqtVKApSLYc521i8SWRTrG6NvhjxuNzRr44tVdB2kXqsrhVz0Rr2lSFYkaJlDAgMCMkEgg79NE4+0eop+8it/DNKDzYEdLD3PJgA8pCuScDHh46VO2Cjjbjzih3utwkiSGaoEZqCiRyGTkCgLjYEHrrRapBb0no+H66f0RaiU86qphIyBlX5cv8AVOTj56jLgct+ZiMb9B2HHPF0Td16ggaYnKqCxJ9x9vWit7SeMLNSzVVXa6aMwQs5DqzZ5QSMgP5jfS3T199rhiink7tWKs/IkfOw2JGTnGo1Xd7rEUpaqqgqY3mi72mhCO59tfrBVGfmw0KxS/xDGkNXDfEPFVBbKO2wW2Clp6eJVdzTzOWY7s3sNjJYk6NycYW/vkjrK+4pVAEAeimPm8wOZX/fpeir7/VRMklxp6WMn6kEK8/vBwcDWrna3QyTd/SU4bZ5pYCM/Fi+T8NRKDarmGxSj6fuGKm+cPnCNbKusO5Z6yGSVl3/AEQfZGhly42t1M8Zp7CqzjKxPNDIWcnfA5UU+A2B1Apqi7XLHJLDDTH+ukp2Vz71QsfvOB8dFKalpKIGdJDLO3stO55pG92RsB7hga6OijNpy3BlqnFVEXpOIb1cZh6VAKeBDzhBQufaz1A5SPDqTnR9eJq6OjWUVMyMMEhaIA9DgKAM5xqTPcmpuXJkJOMICV29+tIrzVMkcJIPMVabLELny3Az8enj5aOemxQonBlzSWwK9cV7zmpNfPTT8wHLHSyFiT4u3Lysf2D9ujVs45S02eeOWherqTkx81HIeckdXzud87DA0uXHjAUF6NHHHV1TxRmSoxIAsCD9J2yAoI38dBJO1emWdh6NWwsM+xKRzL7sFttStJCa3iw5aiUNuZDD/wAJbtIoi9KnpdyxWmpHUBj5YG2PAD79e2uernngp5amoamWXvmjaJo1dhk+1nr9/jpeHapBsFgnON9wOn4tYN2rQyoOaCdlGTgquP36dHSwSpLsV3qZv3Iseqraqrj7uasnkjA5QplJUDyx5aBTcNUKxxeiSS04RSCBJzAk9SMjbx0qDtOhAx6JIcHphR/HXv8ASdCcj0GbHQk4B01Yq6JiJTvq1+4yrZaunkKrOJV6kOB924OtBoamlkkZKOLlcAsYyF5jk9Rt4aCDtVj6tROx8iw/lrY3azGF/wCTpW26Bxt+zTY3F2r/AGFyjFqm1+4VcVv/AEGY/BlP8dfRpXmROa21KgkZJZAB+3QcdrMYUL6tlOP1wPH4a9XtXHNtbm/1o/s6f4+T79hHgQ+6/cZKOsrEuKyy2yseDJBw4Hs4IG49+DqNRVPEEcFVTPS1z0sk6yxoQoK+yQwyDuN/lj36CN2ssGwbSxz497t/sba8btgYHlFoJ+E//l6CWSb/APBihjXqv3CretGlytoqQ3NkdOvnohxjPeL9UWuanpKyUU9JCsqyrgI65yB8dLR7YakKcWsH3ekf+Xrxu2CpP1bXH/8Akn+xqJSnJ7vsdFY0qtBYQ3gRk+p6l2A6AjfWtKW+BTizVGSc4LgY/boO3a9XZIFspx8Z2P8AuawPa9ctj6upfh3zZ/doufJ93+wPJiXqhltvrKmrIp6izSyIhyVE4Unb4ajS266SBh6rRkYMpVp+qspUg7eTHQGTtYu/X0Kk+bsdaj2rXkjIoaQZ8w5/3tdzT+5FY/uu4RoOD5bZWx1tHw/SxToGAcVTk4ZSp6nHQ+Wp/qe9GpE3q+JfowhU1AJ2Oc7DS4vape5PZWmpcnoFjdif+/rFu0riNyQsEQHup2/i+oi5x6M6SxvdtdzoDsYpayltNYKyCOFmudKVCSc+evu0DvFsoWu9cTRUJJqJCSadSfrH3akf3Pl/uV+s1ye4qFaK6UYQBOXY82fE6hXmqhF3rgZ4wRUSeP6x1Rzylztss4orlSj0Kj7WoYbp2vcQPUv3KJcHgTk5pJMoebmGSeXOemMbdNTbNcIGgVaUXCrlMjsZZJmUH223Zjtnp00F7YKiaTtc4ngeRmh9ZN9GxyvXy6ag2+vquWGITusascIp5QPbPgNDJ+g1OhqtdFPJFL3lxqI4WkZVSGYnmA2yT57a2XC0xRW6RIqquRQpIXvgAx6752zt10DtV0rMzr6Q5VQSAcEDbUm2QR3ziinguKCpiECvyN0zjrgaDdhPJFRuggt1rrofRrLVVhjXKyTzynl/d+77hozb7VBHOk9XUz1tUn1ZZN1T3Kv8SSffqQKeKNO7RFVE2VV2CgdMDROBF9Bjl5RzkDLHrpscdbiHO9zF2mJUqxIPUueusZKgqAVkVm8cZwPv1jLK6wNhiMkAnx0Oqd54oj9R2IZfMYzrsmVxWxa02BTds+llepyxclD+l/nfD3e/QW/Xt6Ix22gRZbhOMRp+jEvTnPu64GiswAilOBlUJG3TVc2uaR7Rea5nJqXmKGX9LGcYz8Ndp8fiy3HarP4OOooJrJHRQRxc3eQLKXLyDPpM/jIQeqKfvIGs6avmpn5o+HVfnbn55Jjzy+ZJ8ydQ7x7CEKSO69hMH6oCqQB95+/QWesqvVlJUel1PeyyyF2Mrbn2ffq65U6XQwJOUm7HKZrpeKaSlFriolZfalWTnbA64GNBrtaYrb6PCqq85+kcFT9Xy/Z46Bx3u5xUoCXCrXLb4lb+ej/D2a5o56pmmlcDmaRiScFtOTUlSK8047sHNTOss8QVHAOc8n1c+A1nDFSRIeeOd26/UXRjpXTjAw8jc3vxjGttVFG0UmUXbGNvjovD2F+ISaCzUtRPS0JpEkkeoJldvZZYwQCAR7iT92iHFvBMFo4oulvoKXNLCFMfOQxCkkZz7tFrZDGLpbzyKS/dFidyeYjP35Op3ETtXcSPLUMXeSlJduhbDP1x8BqIrzIKU/IyuBal9JaNYQABtzDqderaG9Hado1wsvdkBc76eKGlh9Nlm7pTIEhIYjJ3I0btFDTTR1U0kKM7zsWyNiR7umnNUxCm2VcKOE05lZIwwbGAgz89PPCXDccNBUx1tFTTPMEKExKxAZT0JHw0HskSPxC8TKCnO45cbYwdPkDsqogxgQgjIBPTz0MkHB77iLYOEoTd2jrGSRoXY9zgYflIxv8Afo7xNw0tfV00hFJS2+mjYyS4Cf8ArppiSkgR5J1iUSugLOBudQrtTQVkEMNRDHLE1SoKOoIOObG2gkg4y2KxntgpYpahWilQTmGI8w9pevMB5YxqElM6bHlwTnTFeYkiCxovKizOFUdAMDQ8AcoyM+346cooVzG3hK3+lXtIMpGGRwpI2BxofVUUlFWywk5dCVz19ncabODEVrjLkfV5ce72tY8YwRJfXZI1UkIdhjfHXQ7Hc4sJbJYpI1jp0WUgHmWAAr4dRqXceErhBVd2KiMcz4A7vHs9Obr5g6sJKSGS71DMpJdUDe0dwTorxBDHPH3si8zx2+MoSfqkyPnGhboKFysm/wBz3QTUNnuSzS95zXSjIOANt/edLl9qQL3cByJtUy+H6x09djkax2mp5Rj/AAjRjrnw1V/EMr+v7lv/AJVL/tnWVqleR0bOkl/CjZ//2Q==" alt="Property photo 15"></div>
                </div>

                <div class="lightbox" id="photoLightbox" aria-hidden="true">
        <div class="lightbox-dialog" role="dialog" aria-modal="true" aria-label="Photo viewer">

            <div class="lightbox-top">
                <span id="lightboxCounter">1 / 108 Photos</span>
                <button type="button" class="lightbox-close"
                    onclick="closeLightbox()" aria-label="Close">&times;</button>
            </div>

            <div class="lightbox-stage">
                <button type="button" class="lightbox-arrow prev"
                    onclick="changeLightbox(-1)" aria-label="Previous photo">&#8249;</button>

                <img id="lightboxImage" src="" alt="Selected property photo">

                <button type="button" class="lightbox-arrow next"
                    onclick="changeLightbox(1)" aria-label="Next photo">&#8250;</button>
            </div>

            <div class="lightbox-thumbnails" id="lightboxThumbnails"></div>

        </div>
    </div>

            </div>
            <div class="gallery-footer">
                <span>Displaying: 1-20 of 108 Photos</span>
                <div class="pagination">
                    <button class="page-btn">&#8249;</button><button class="page-btn active">1</button><button class="page-btn">2</button><button class="page-btn">3</button><button class="page-btn">...</button><button class="page-btn">10</button><button class="page-btn">&#8250;</button>
                </div>
                <select class="per-page"><option>20 per page</option><option>40 per page</option><option>60 per page</option></select>
            </div>
        </div>
    </section>
</main>

<footer class="bottom-bar">
    <div class="last-updated"><span>&#8635;</span>Last updated Sep 08, 2026, 4:30 AM</div>
    <div class="bottom-actions"><button class="more-btn" type="button" title="More options">&#8943;</button><button class="save-btn" type="button" onclick="showToast('Design prototype: changes are not saved to the CRM yet.')">Save Changes</button></div>
</footer>
<div class="toast" id="toast"></div>
<div class="modal-backdrop" id="noteModal">
    <div class="modal"><h3>Add Property Note</h3><textarea id="noteText" placeholder="Write a note about this property..."></textarea><div class="modal-actions"><button onclick="closeModal('noteModal')">Cancel</button><button class="primary" onclick="addNote()">Add Note</button></div></div>
</div>
<div class="modal-backdrop" id="categoryModal">
    <div class="modal"><h3>Create New Category</h3><input id="newCategoryName" type="text" placeholder="Category name"><div class="modal-actions"><button onclick="closeModal('categoryModal')">Cancel</button><button class="primary" onclick="createCategory()">Create Category</button></div></div>
</div>

<script>
function showToast(message) {
    var toast = document.getElementById('toast');
    toast.innerHTML = message;
    toast.style.display = 'block';
    window.setTimeout(function(){ toast.style.display = 'none'; }, 2800);
}
function openModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }
function addNote() {
    var input = document.getElementById('noteText');
    var value = input.value.replace(/^\s+|\s+$/g, '');
    if (!value) { input.focus(); return; }
    var note = document.createElement('div');
    note.className = 'note';
    var p = document.createElement('p'); p.appendChild(document.createTextNode(value));
    var meta = document.createElement('div'); meta.className = 'note-meta'; meta.appendChild(document.createTextNode('You • Just now'));
    note.appendChild(p); note.appendChild(meta);
    document.getElementById('notesBody').insertBefore(note, document.getElementById('notesBody').firstChild);
    input.value = ''; closeModal('noteModal'); showToast('Note added to this prototype.');
}
function createCategory() {
    var input = document.getElementById('newCategoryName');
    var value = input.value.replace(/^\s+|\s+$/g, '');
    if (!value) { input.focus(); return; }
    showToast('Category "' + value + '" added to this prototype.');
    input.value = ''; closeModal('categoryModal');
}
function toggleGroup(button) {
    var group = button.parentNode;
    var items = group.getElementsByClassName('group-items')[0];
    var arrow = button.getElementsByClassName('chevron')[0];
    if (items.style.display === 'none') { items.style.display = 'block'; arrow.innerHTML = '&#8964;'; }
    else { items.style.display = 'none'; arrow.innerHTML = '&#8250;'; }
}
function toggleAllGroups() {
    var groups = document.getElementsByClassName('category-group');
    var shouldExpand = false;
    for (var i=0; i<groups.length; i++) {
        var item = groups[i].getElementsByClassName('group-items')[0];
        if (item && item.style.display === 'none') { shouldExpand = true; }
    }
    for (var j=0; j<groups.length; j++) {
        var items = groups[j].getElementsByClassName('group-items')[0];
        var arrow = groups[j].getElementsByClassName('chevron')[0];
        if (items) { items.style.display = shouldExpand ? 'block' : 'none'; }
        if (arrow) { arrow.innerHTML = shouldExpand ? '&#8964;' : '&#8250;'; }
    }
}
function removeTag(button) {
    var item = button.parentNode;
    item.parentNode.removeChild(item);
    showToast('Tag removed from the visual selection. Save is not connected yet.');
}
function filterAssigned() {
    var query = document.getElementById('assignedSearch').value.toLowerCase();
    var groups = document.getElementsByClassName('category-group');
    for (var i=0; i<groups.length; i++) {
        var text = groups[i].innerText || groups[i].textContent;
        groups[i].style.display = text.toLowerCase().indexOf(query) >= 0 ? '' : 'none';
    }
}
function filterCategories() {
    var query = document.getElementById('categorySearch').value.toLowerCase();
    var choices = document.getElementsByClassName('category-choice');
    for (var i=0; i<choices.length; i++) {
        var text = choices[i].innerText || choices[i].textContent;
        choices[i].style.display = text.toLowerCase().indexOf(query) >= 0 ? '' : 'none';
    }
}
function setPill(button, groupName) {
    var parent = button.parentNode;
    var buttons = parent.getElementsByClassName('pill');
    for (var i=0; i<buttons.length; i++) { buttons[i].className = 'pill'; }
    button.className = 'pill active';
}
function selectCategory(element, name) {
    var choices = document.getElementsByClassName('category-choice');
    for (var i=0; i<choices.length; i++) { choices[i].className = 'category-choice'; }
    element.className = 'category-choice selected';
    document.getElementById('subcategoryTitle').innerHTML = name;
}
(function(){
    var photos = document.getElementById('photoGrid').getElementsByClassName('photo');
    for (var i=0; i<photos.length; i++) {
        photos[i].onclick = function() {
            if (this.className.indexOf('selected') >= 0) {
                this.className = 'photo';
            } else {
                this.className = 'photo selected';
            }
        };
    }
    var checks = document.getElementsByClassName('subcategory');
    for (var j=0; j<checks.length; j++) {
        checks[j].getElementsByTagName('input')[0].onchange = function() {
            var label = this.parentNode;
            label.className = this.checked ? 'subcategory checked' : 'subcategory';
        };
    }
})();

// Dark mode: persist the user's choice in this browser.
(function(){
    var toggle = document.getElementById('themeToggle');
    var saved = null;
    try { saved = localStorage.getItem('property-tagging-theme'); } catch(e) {}
    if (saved === 'dark') { document.body.className += ' dark-theme'; if (toggle) toggle.innerHTML = '&#9728;'; }
    if (toggle) {
        toggle.onclick = function() {
            var isDark = document.body.className.indexOf('dark-theme') >= 0;
            if (isDark) {
                document.body.className = document.body.className.replace(/\s*dark-theme/g,'');
                toggle.innerHTML = '&#9790;';
                try { localStorage.setItem('property-tagging-theme','light'); } catch(e) {}
            } else {
                document.body.className += ' dark-theme';
                toggle.innerHTML = '&#9728;';
                try { localStorage.setItem('property-tagging-theme','dark'); } catch(e) {}
            }
        };
    }
})();

// Mobile section navigation.
function setMobileTab(tab) {
    var body = document.body;
    body.className = body.className.replace(/\s*mobile-tab-[a-z-]+/g,'').replace(/\s*mobile-add-categories/g,'');
    body.className += ' mobile-tab-' + tab;
    var tabs = document.getElementsByClassName('mobile-tab');
    for (var i=0; i<tabs.length; i++) {
        tabs[i].className = tabs[i].getAttribute('data-tab') === tab ? 'mobile-tab active' : 'mobile-tab';
    }
}
(function(){
    var tabs = document.getElementsByClassName('mobile-tab');
    for (var i=0; i<tabs.length; i++) {
        tabs[i].onclick = function(){ setMobileTab(this.getAttribute('data-tab')); };
    }
    if (window.innerWidth <= 760) { setMobileTab('overview'); }
    window.addEventListener('resize', function(){
        if (window.innerWidth > 760) {
            document.body.className = document.body.className.replace(/\s*mobile-tab-[a-z-]+/g,'').replace(/\s*mobile-add-categories/g,'');
        } else if (!/mobile-tab-/.test(document.body.className)) {
            setMobileTab('overview');
        }
    });
})();
function showMobileAddCategories() {
    var panel = document.querySelector('.add-category-panel');
    if (panel) {
        panel.className += ' mobile-show-subcategories';
        document.body.className = document.body.className.replace(/\s*mobile-tab-[a-z-]+/g,'') + ' mobile-add-categories';
    }
}
function selectCategory(element, name) {
    var choices = document.getElementsByClassName('category-choice');
    for (var i=0; i<choices.length; i++) { choices[i].className = 'category-choice'; }
    element.className = 'category-choice selected';
    document.getElementById('subcategoryTitle').innerHTML = name;
    var panel = document.querySelector('.add-category-panel');
    if (window.innerWidth <= 760 && panel) { panel.className += ' mobile-show-subcategories'; }
}
function backToCategoryList() {
    var panel = document.querySelector('.add-category-panel');
    if (panel) panel.className = panel.className.replace(/\s*mobile-show-subcategories/g,'');
}

// Photo viewer / lightbox.
var lightboxImages = [];
var lightboxIndex = 0;
function collectLightboxImages() {
    lightboxImages = [];
    var imgs = document.getElementById('photoGrid').getElementsByTagName('img');
    for (var i=0; i<imgs.length; i++) lightboxImages.push(imgs[i].src);
}
function openLightbox(index) {
    collectLightboxImages();

    if (!lightboxImages.length) return;

    lightboxIndex = index;
    renderLightbox();

    var box = document.getElementById('photoLightbox');

    box.className = 'lightbox open';
    box.setAttribute('aria-hidden', 'false');
}
function renderLightbox() {
    document.getElementById('lightboxImage').src = lightboxImages[lightboxIndex];
    document.getElementById('lightboxCounter').innerHTML = (lightboxIndex + 1) + ' / ' + lightboxImages.length + ' Photos';
    var thumbs = document.getElementById('lightboxThumbnails');
    thumbs.innerHTML = '';
    for (var i=0; i<lightboxImages.length; i++) {
        var im = document.createElement('img');
        im.src = lightboxImages[i];
        im.alt = 'Photo ' + (i+1);
        if (i === lightboxIndex) im.className = 'active';
        im.setAttribute('data-index',i);
        im.onclick = function(){ lightboxIndex = parseInt(this.getAttribute('data-index'),10); renderLightbox(); };
        thumbs.appendChild(im);
    }
    var active = thumbs.getElementsByClassName('active')[0];
    if (active && active.scrollIntoView) active.scrollIntoView({block:'nearest',inline:'center'});
}
function changeLightbox(step) {
    if (!lightboxImages.length) return;
    lightboxIndex = (lightboxIndex + step + lightboxImages.length) % lightboxImages.length;
    renderLightbox();
}
function closeLightbox() {
    var box = document.getElementById('photoLightbox');

    if (!box) return;

    box.className = 'lightbox';
    box.setAttribute('aria-hidden', 'true');
}
(function(){
    var grid = document.getElementById('photoGrid');
    if (!grid) return;
    var photos = grid.getElementsByClassName('photo');
    for (var i=0; i<photos.length; i++) {
        (function(index){
            photos[index].onclick = function(){ openLightbox(index); };
        })(i);
    }
    var overlay = document.getElementById('photoLightbox');
    overlay.onclick = function(e){ if (e.target === overlay) closeLightbox(); };
    document.addEventListener('keydown',function(e){
        if (e.key === 'Escape') closeLightbox();
        if (document.getElementById('photoLightbox').className.indexOf('open') >= 0) {
            if (e.key === 'ArrowRight') changeLightbox(1);
            if (e.key === 'ArrowLeft') changeLightbox(-1);
        }
    });
})();

</script>
</body>
</html>
