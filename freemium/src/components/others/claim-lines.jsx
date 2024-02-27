import * as React from 'react';
import PropTypes from 'prop-types';

import Paper from '@mui/material/Paper';
import Table from '@mui/material/Table';
import TableRow from '@mui/material/TableRow';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableHead from '@mui/material/TableHead';
import TableContainer from '@mui/material/TableContainer';
 
export default function ClaimlinesTable({claim_lines}) {
  return (
    <TableContainer component={Paper}>
      <Table sx={{ minWidth: 650 }} aria-label="simple table">
        <TableHead>
          <TableRow>
            <TableCell align="right">Treatment Date</TableCell>
            <TableCell align="right">Procedure Code</TableCell>
            <TableCell align="right">ICD10</TableCell>
            <TableCell align="right">PMB</TableCell>
            <TableCell align="right">Charged Amount</TableCell>
            <TableCell align="right">Scheme Amount</TableCell>
            <TableCell align="right">Gap Amount</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {claim_lines.length>0?claim_lines.map((claim_line,index) => (
            <TableRow
              key={index}
              sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
            >             
              <TableCell align="right">{claim_line.treatmentDate}</TableCell>
              <TableCell align="right">{claim_line.tariff_code}</TableCell>
              <TableCell align="right">{claim_line.primaryICDCode}</TableCell>
              <TableCell align="right">{claim_line.PMBFlag}</TableCell>
              <TableCell align="right">{claim_line.clmnline_charged_amnt}</TableCell>
              <TableCell align="right">{claim_line.clmline_scheme_paid_amnt}</TableCell>
              <TableCell align="right">{claim_line.gap}</TableCell>
            </TableRow>
          )):<TableRow><TableCell colSpan="7" align="center" style={{color:"red"}}>No Claim Lines</TableCell></TableRow>}
        </TableBody>
      </Table>
    </TableContainer>
  );
}
ClaimlinesTable.propTypes = {
  claim_lines: PropTypes.any,
};