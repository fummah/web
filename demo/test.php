<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styled Table</title>
    <style>body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 14px;
    min-width: 1000px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    background-color: #ffffff;
}

.styled-table thead tr {
    background-color: #009879;
    color: #ffffff;
    text-align: left;
}

.styled-table th,
.styled-table td {
    padding: 12px 15px;
}

.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}

.styled-table tbody tr.active-row {
    font-weight: bold;
    color: #009879;
}

.header-row {
    font-weight: bolder;
    text-align: center;
    color: deepskyblue;
    padding: 10px;
}

.header-text {
    display: flex;
    justify-content: center;
    align-items: center;
}

.add-claim::before {
    content: url('data:image/svg+xml;base64,...'); /* Add SVG icon for plus-circle here */
    cursor: pointer;
    margin-right: 5px;
}

.select-doctor::before {
    content: url('data:image/svg+xml;base64,...'); /* Add SVG icon for comment here */
    cursor: pointer;
    margin: 0 5px;
}

.edit-doctor-form {
    display: inline;
}

.edit-doctor-btn {
    background: none;
    color: inherit;
    border: none;
    padding: 0;
    font: inherit;
    cursor: pointer;
    outline: inherit;
}

.edit-doctor-btn::before {
    content: url('data:image/svg+xml;base64,...'); /* Add SVG icon for pencil here */
}

.doctor-info {
    color: black;
}

.badge {
    background-color: #54bf99;
    padding: 5px 10px;
    border-radius: 3px;
    color: #ffffff;
}

.pmb-icon::before {
    content: url('data:image/svg+xml;base64,...'); /* Add SVG icon for close here */
    color: red;
}

.text-success {
    color: green;
}

.edit-claim::before {
    content: url('data:image/svg+xml;base64,...'); /* Add SVG icon for pencil here */
    cursor: pointer;
    margin-right: 5px;
}

.delete-line::before {
    content: url('data:image/svg+xml;base64,...'); /* Add SVG icon for trash here */
    cursor: pointer;
}
</style>
</head>
<body>
    <table class="styled-table" align="center">
        <thead>
            <tr>
                <th>No.</th>
                <th>CPT4</th>
                <th>Inv.Dat</th>
                <th>Modifier</th>
                <th>Res. Code</th>
                <th>Treat.Date</th>
                <th>PMB?</th>
                <th>Tarif.C</th>
                <th>ICD10</th>
                <th>Chrgd Amt</th>
                <th>Sch. Amt</th>
                <th>Memb.Port</th>
                <th>GAP</th>
                <th>Calc</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="15" class="header-row">
                    <span class="header-text">
                        <span class="add-claim" title="Add new claim line for this doctor"></span>
                        |
                        <span class="select-doctor" title="Select this doctor"></span>
                        |
                        <form class="edit-doctor-form" action="edit_doctor.php" method="post" target="print_popup">
                            <input type="hidden" name="doc_id" value="7320">
                            <button class="edit-doctor-btn" name="doctor_edit_btn" title="Edit this doctor"></button>
                        </form>
                        |
                        <span class="doctor-info">[0961523] CAREN CLAASSENS (0860 199 199/ 01234) [CCS996++**]</span>
                    </span>
                </td>
            </tr>
            <tr>
                <td><span class="badge">1</span></td>
                <td></td>
                <td></td>
                <td></td>
                <td>101 - None</td>
                <td>2024-05-06</td>
                <td><span class="pmb-icon"></span></td>
                <td>0151</td>
                <td>[Z43.2]</td>
                <td>1 414.50</td>
                <td>471.50</td>
                <td class="text-success">943.00</td>
                <td class="text-success">943.00</td>
                <td class="uk-text-warning">0.00</td>
                <td>
                    <span class="edit-claim" title="Edit this claim line"></span>
                    <span class="delete-line" title="Delete this claim line"></span>
                </td>
            </tr>
            <tr>
                <td><span class="badge">1</span></td>
                <td></td>
                <td></td>
                <td></td>
                <td>101 - None</td>
                <td>2024-05-06</td>
                <td><span class="pmb-icon"></span></td>
                <td>0151</td>
                <td>[Z43.2]</td>
                <td>1 414.50</td>
                <td>471.50</td>
                <td class="text-success">943.00</td>
                <td class="text-success">943.00</td>
                <td class="uk-text-warning">0.00</td>
                <td>
                    <span class="edit-claim" title="Edit this claim line"></span>
                    <span class="delete-line" title="Delete this claim line"></span>
                </td>
            </tr>
            <tr>
                <td><span class="badge">1</span></td>
                <td></td>
                <td></td>
                <td></td>
                <td>101 - None</td>
                <td>2024-05-06</td>
                <td><span class="pmb-icon"></span></td>
                <td>0151</td>
                <td>[Z43.2]</td>
                <td>1 414.50</td>
                <td>471.50</td>
                <td class="text-success">943.00</td>
                <td class="text-success">943.00</td>
                <td class="uk-text-warning">0.00</td>
                <td>
                    <span class="edit-claim" title="Edit this claim line"></span>
                    <span class="delete-line" title="Delete this claim line"></span>
                </td>
            </tr>
            <tr>
                <td><span class="badge">1</span></td>
                <td></td>
                <td></td>
                <td></td>
                <td>101 - None</td>
                <td>2024-05-06</td>
                <td><span class="pmb-icon"></span></td>
                <td>0151</td>
                <td>[Z43.2]</td>
                <td>1 414.50</td>
                <td>471.50</td>
                <td class="text-success">943.00</td>
                <td class="text-success">943.00</td>
                <td class="uk-text-warning">0.00</td>
                <td>
                    <span class="edit-claim" title="Edit this claim line"></span>
                    <span class="delete-line" title="Delete this claim line"></span>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
