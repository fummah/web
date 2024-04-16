import * as React from 'react';
import PropTypes from 'prop-types';

import Box from '@mui/material/Box';
import Button from '@mui/material/Button';
import SwipeableDrawer from '@mui/material/SwipeableDrawer';

import Iconify from 'src/components/iconify';

import FormUpload from 'src/sections/overview/upload';

const SwipeableTemporaryDrawer = React.memo(({ myvariant, mycolor, mytext,plan}) => {
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
    <FormUpload/>
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
            {list(anchor)}   
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