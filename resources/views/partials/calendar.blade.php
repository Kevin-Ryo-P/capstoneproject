<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
<div class="wrapper">
    <header>
      <p class="current-date"></p>
      <div class="icons">
        <span id="prevcal" class="material-symbols-rounded">chevron_left</span>
        <span id="nextcal" class="material-symbols-rounded">chevron_right</span>
      </div>
    </header>
    <div class="calendar">
      <ul class="weeks">
        <li>Sun</li>
        <li>Mon</li>
        <li>Tue</li>
        <li>Wed</li>
        <li>Thu</li>
        <li>Fri</li>
        <li>Sat</li>
      </ul>
      <ul class="days"></ul>
    </div>
  </div>
<script src="{{ asset('js/calendar.js') }}">
</script>