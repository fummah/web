import * as React from 'react';
import PropTypes from 'prop-types';

import Box from '@mui/material/Box';
import Link from '@mui/material/Link';
import Paper from '@mui/material/Paper';
import Button from '@mui/material/Button';
import Typography from '@mui/material/Typography';
import SwipeableDrawer from '@mui/material/SwipeableDrawer';
import CreditScoreIcon from '@mui/icons-material/CreditScore';

import Iconify from 'src/components/iconify';

import QueryForm from 'src/sections/forms/query_form';

const SwipeableTemporaryDrawer = React.memo(({ myvariant, mycolor, mytext,plan }) => {
  const [state, setState] = React.useState({
    top: false,
    left: false,
    bottom: false,
    right: false,
  });

  const toggleDrawer = (anchor, open) => (event) => {
    if (
      event &&
      event.type === 'keydown' &&
      (event.key === 'Tab' || event.key === 'Shift')
    ) {
      return;
    }
    setState({ ...state, [anchor]: open });
  };

  const list = (anchor) => (
    <Box
      sx={{ width: anchor === 'top' || anchor === 'bottom' ? 'auto' : "100%" }}
      role="presentation"
    >
    <QueryForm/>

    </Box>
  );

  return (
    <div>
      {['right'].map((anchor) => (
        <React.Fragment key={anchor}>
          <Button variant={myvariant} color={mycolor} startIcon={<Iconify icon="eva:plus-fill" />} onClick={toggleDrawer(anchor, true)}>{mytext}</Button>
          <SwipeableDrawer
            anchor={anchor}
            open={state[anchor]}
            onClose={toggleDrawer(anchor, false)}
            onOpen={toggleDrawer(anchor, true)}
          >
            {plan !== null?list(anchor):
      <Paper
      sx={{
        textAlign: 'center',
        margin:'20px',
      }}
    >
       <Typography variant="body2" paragraph>
            No Active Plan found!
          </Typography>
      <Link href="https://medclaimassist.co.za/testwp/product/annual-fee" target="_blank" rel="noopener noreferrer">
         <Button style={{marginTop:'10px'}} variant="outlined" color="error">
       <CreditScoreIcon/> Subscribe Now
      </Button> 
      </Link>
    </Paper>}
            <Button onClick={toggleDrawer(anchor, false)}>Close</Button>
          </SwipeableDrawer>
        
        </React.Fragment>
      ))
      }
      
    </div>
  );
});

SwipeableTemporaryDrawer.propTypes = {
  myvariant: PropTypes.any,
  mycolor: PropTypes.any,
  mytext: PropTypes.any,
  plan: PropTypes.any,
};
export default SwipeableTemporaryDrawer;