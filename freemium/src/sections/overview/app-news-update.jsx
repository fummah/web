import { useState } from 'react';
import PropTypes from 'prop-types';

import Box from '@mui/material/Box';
import Link from '@mui/material/Link';
import Card from '@mui/material/Card';
import Stack from '@mui/material/Stack';
import Button from '@mui/material/Button';
import Divider from '@mui/material/Divider';
import Typography from '@mui/material/Typography';
import CardHeader from '@mui/material/CardHeader';

import Iconify from 'src/components/iconify';
import Scrollbar from 'src/components/scrollbar';

// ----------------------------------------------------------------------

export default function AppNewsUpdate({ title, subheader, list,count, ...other }) {
  const [selected, setSelected] = useState(['2']);
  const handleClickComplete = (newsId) => {
    const newsCompleted = selected.includes(newsId)
      ? selected.filter((value) => value !== newsId)
      : [...selected, newsId];

    setSelected(newsCompleted);
  };

  return (
    <Card {...other}>
      <CardHeader title={title} subheader={subheader} />

      <Scrollbar>
        <Stack spacing={3} sx={{ p: 3, pr: 0 }}>
          {count>0? list.map((claims) => (
            <NewsItem key={claims[0].claim_header.claim_id} claim_number={claims[0].claim_number} service_date={claims[0].claim_header.Service_Date} charged_amnt={claims[0].claim_header.charged_amnt} onChange={() => handleClickComplete(claims.id)}/>
          )):<Typography variant="body2" sx={{ mt: 2, mb: 5 }}>No Switch Claims</Typography>}
        </Stack>
      </Scrollbar>

      <Divider sx={{ borderStyle: 'dashed' }} />
{count>0?<Box sx={{ p: 2, textAlign: 'right' }}>
        <Button
          size="small"
          color="inherit"
          endIcon={<Iconify icon="eva:arrow-ios-forward-fill" />}
        >
        <Link href="/switch-claims">View All</Link>
        </Button>
      </Box>:null
    }
    </Card>
  );
}

AppNewsUpdate.propTypes = {
  title: PropTypes.string,
  subheader: PropTypes.string,
  count: PropTypes.number,
  list: PropTypes.array.isRequired,
};

// ----------------------------------------------------------------------

function NewsItem({ claim_number, service_date, charged_amnt }) {

    
  return (
 
    <Stack direction="row" alignItems="center" spacing={2}>
      <Box
        component="img"
        alt={claim_number}
        src="/assets/icons/glass/ic_claim.png"
        sx={{ width: 48, height: 48, borderRadius: 1.5, flexShrink: 0 }}
      />

      <Box sx={{ minWidth: 240, flexGrow: 1 }}>
        <Link color="inherit" variant="subtitle2" underline="hover" noWrap>
          {service_date}
        </Link>

        <Typography variant="body2" sx={{ color: 'text.secondary' }} noWrap>
          {charged_amnt}
        </Typography>
      </Box>
    </Stack>  
   
  );
}

NewsItem.propTypes = {
      claim_number: PropTypes.any,
      service_date: PropTypes.any,
    charged_amnt: PropTypes.any,  
};

