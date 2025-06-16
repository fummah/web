
  <style>
  
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 6px 12px;
      text-align: right;
    }
    th:first-child, td:first-child {
      text-align: left;
    }
    .toggle {
      cursor: pointer;
      font-weight: bold;
      margin-right: 5px;
    }
    .child-row {
      display: none;
    }
    .subtotal {
      background: #f1f1f1;
      font-weight: bold;
    }
    .grand-total {
      background: #d0e0ff;
      font-weight: bold;
    }

  .filter-panel {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 12px;
  background-color: #f9f9f9;
  padding: 15px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.filter-item {
  display: flex;
  align-items: center;
  gap: 10px;
}

.filter-item label {
  width: 130px;
  font-size: 13px;
  color: #333;
  flex-shrink: 0;
}

.filter-item select {
  flex-grow: 1;
  padding: 4px 8px;
  font-size: 14px;
}

  </style>


<h2>Clinical</h2>

<div class="filter-panel">
  <div class="filter-item">
    <label>Claim Date</label>
    <select>
      <option>(All)</option>
    </select>
  </div>
  <div class="filter-item">
    <label>Length of Stay Status</label>
    <select>
      <option>(All)</option>
    </select>
  </div>
  <div class="filter-item">
    <label>Discipline Status</label>
    <select>
      <option>Reject</option>
      <option>Accept</option>
    </select>
  </div>
  <div class="filter-item">
    <label>DOB Status</label>
    <select>
      <option>(All)</option>
    </select>
  </div>
  <div class="filter-item">
    <label>Gender Status</label>
    <select>
      <option>(All)</option>
    </select>
  </div>
  <div class="filter-item">
    <label>ICD/Tariff Status</label>
    <select>
      <option>(All)</option>
    </select>
  </div>
  <div class="filter-item">
    <label>CPT Status</label>
    <select>
      <option>(All)</option>
    </select>
  </div>
  <div class="filter-item">
    <label>Duplicate</label>
    <select>
      <option>(All)</option>
    </select>
  </div>
</div>



<table>
  <thead>
    <tr>
      <th>Row Labels</th>
      <th>Sum of Claimed Amount</th>
      <th>Sum of Paid Amount</th>
      <th>Count of Claim Number</th>
    </tr>
  </thead>
  <tbody>
    <!-- Auxilliary -->
    <tr>
      <td>Auxilliary</td>
      <td>2060.90</td>
      <td>0.00</td>
      <td>12</td>
    </tr>

    <!-- General Practice -->
    <tr class="subtotal" onclick="toggleGroup('gp')">
      <td><span class="toggle" id="toggle-gp">+</span>General Practice</td>
      <td>22018.84</td>
      <td>18120.10</td>
      <td>272</td>
    </tr>
    <tr class="child-row gp">
      <td>&nbsp;&nbsp;&nbsp;0084</td>
      <td>223.00</td>
      <td>0.00</td>
      <td>2</td>
    </tr>
    <tr class="child-row gp">
      <td>&nbsp;&nbsp;&nbsp;181a</td>
      <td>4737.90</td>
      <td>4416.19</td>
      <td>13</td>
    </tr>
    <tr class="child-row gp">
      <td>&nbsp;&nbsp;&nbsp;MDSCR</td>
      <td>1302.55</td>
      <td>73.91</td>
      <td>148</td>
    </tr>
    <tr class="child-row gp">
      <td>&nbsp;&nbsp;&nbsp;meds</td>
      <td>695.39</td>
      <td>0.00</td>
      <td>44</td>
    </tr>
    <tr class="child-row gp">
      <td>&nbsp;&nbsp;&nbsp;ncon</td>
      <td>0.00</td>
      <td>0.00</td>
      <td>1</td>
    </tr>
    <tr class="child-row gp">
      <td>&nbsp;&nbsp;&nbsp;vcons</td>
      <td>15060.00</td>
      <td>13630.00</td>
      <td>64</td>
    </tr>

    <!-- Hospital -->
    <tr>
      <td>Hospital</td>
      <td>936.35</td>
      <td>936.35</td>
      <td>10</td>
    </tr>

    <!-- Pathology -->
    <tr>
      <td>Pathology</td>
      <td>216.30</td>
      <td>0.00</td>
      <td>1</td>
    </tr>

    <!-- Specialist -->
    <tr>
      <td>Specialist</td>
      <td>1997.87</td>
      <td>0.61</td>
      <td>3</td>
    </tr>

    <!-- Grand Total -->
    <tr class="grand-total">
      <td>Grand Total</td>
      <td>27230.26</td>
      <td>19057.06</td>
      <td>298</td>
    </tr>
  </tbody>
</table>

<script>
  function toggleGroup(group) {
    const rows = document.querySelectorAll(`.child-row.${group}`);
    const toggleIcon = document.getElementById(`toggle-${group}`);
    const isHidden = rows[0].style.display === "none";

    rows.forEach(row => {
      row.style.display = isHidden ? "table-row" : "none";
    });

    toggleIcon.textContent = isHidden ? "−" : "+";
  }

    document.querySelectorAll('.filter-item select').forEach(select => {
    select.addEventListener('change', () => {
      console.log(`${select.previousElementSibling.textContent}: ${select.value}`);
      // Add logic to filter the table here
    });
  });
</script>

