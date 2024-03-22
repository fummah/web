import * as React from 'react';
import PropTypes from 'prop-types';

import Paper from '@mui/material/Paper';
import Table from '@mui/material/Table';
import TableRow from '@mui/material/TableRow';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableHead from '@mui/material/TableHead';
import TableContainer from '@mui/material/TableContainer';

export default function querylinesTable({query_lines}) {
  return (
    <TableContainer component={Paper}>
      <Table sx={{ minWidth: 650 }} aria-label="simple table">
        <TableHead>
          <TableRow>
            <TableCell align="right">Treatment Date</TableCell>
            <TableCell align="right">Paid From</TableCell>
            <TableCell align="right">Amount Charged</TableCell>
            <TableCell align="right">Amount Paid</TableCell>
            <TableCell align="right">Date Entered</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {query_lines.length>0?query_lines.map((query_line,index) => (
            <TableRow
              key={index}
              sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
            >      
              <TableCell align="right">{query_line.treatment_date}</TableCell>
              <TableCell align="right">{query_line.paid_from}</TableCell>
              <TableCell align="right">{query_line.amount_charged}</TableCell>
              <TableCell align="right">{query_line.amount_paid}</TableCell>
              <TableCell align="right">{query_line.date_entered}</TableCell>
            </TableRow>
          )):<TableRow><TableCell colSpan="7" align="center" style={{color:"red"}}>No Query Lines</TableCell></TableRow>}
        </TableBody>
      </Table>
    </TableContainer>
  );
}
querylinesTable.propTypes = {
  query_lines: PropTypes.any,
};