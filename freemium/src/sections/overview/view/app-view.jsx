import { Tour } from 'antd';
import { useRef,useState, useEffect } from 'react';

import Container from '@mui/material/Container';
import Grid from '@mui/material/Unstable_Grid2';
import Typography from '@mui/material/Typography';

import useAxiosFetch from 'src/hooks/use-axios';

import Error from 'src/components/response/error';
import Loader from 'src/components/response/loader';

import AppNewsUpdate from '../app-news-update';
import AppOrderTimeline from '../app-order-timeline';
import AppCurrentVisits from '../app-current-visits';
import AppWidgetSummary from '../app-widget-summary';
import AppConversionRates from '../app-conversion-rates';

// ----------------------------------------------------------------------

export default function AppView() {
  const ref1 = useRef(null);
  const ref2 = useRef(null);
  const ref3 = useRef(null);
  const [open, setOpen] = useState(true);
const [totalqueries, setTotalqueries] = useState(0);
const [totalclaims, setTotalclaims] = useState(0);
const [totalswitchclaims, setTotalswitchclaims] = useState(0);
const [trail, setTrail] = useState([]);
const [switchclaims, setSwitchclaims] = useState([]);
const [graph2, setGraph2] = useState([]);
const [user, setUser] = useState({});
const { isLoading: isLoadingDashboard, isError: isErrorDashboard, data: dataDashboard,statusCode:statusCodeDashboard } = useAxiosFetch('getdashboard','GET', {});

useEffect(() => {
 
    if(dataDashboard && statusCodeDashboard===200)
    {
      setTotalqueries(dataDashboard.total_query);
      setTotalclaims(dataDashboard.total_claims);
      setTotalswitchclaims(dataDashboard.total_switch_claims.original.length);
      setTrail(dataDashboard.trail);
      setSwitchclaims([dataDashboard.total_switch_claims.original]); 
      setUser(dataDashboard.user)
      const ccsGrouperDescArray = dataDashboard.total_switch_claims.original.flatMap(item => item.claim_lines.map(line => line.ccs_grouper_desc));

      setGraph2(getGraph2(ccsGrouperDescArray)); 
      console.log(dataDashboard);
    }   
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [dataDashboard]); 

    const getGraph2 = (ccsGrouperDescArray=[]) =>{    
      const groupedArray = ccsGrouperDescArray.reduce((acc, curr) => {
        const existingGroup = acc.find(item => item.label === curr);
        if (existingGroup) {
          existingGroup.value+=1;
        } else {
          acc.push({ label: curr, value: 1 });
        }
        return acc;
      }, []);
      return groupedArray;
    };
    const steps = [
      {
        title: 'Switch Claims',
        description: 'These are claims from our Switches',       
        target: () => ref1.current,
      },
      {
        title: 'FAQs',
        description: 'Frequently asked question about the medical payment',
        target: () => ref2.current,
      },
      {
        title: 'Tips',
        description: 'Hints about your claims',
        target: () => ref3.current,
      },
    ];
  return (
    <Container maxWidth="xl">
      <Typography variant="h4" sx={{ mb: 5 }}>
        Hi, {user.first_name} 👋
      </Typography>
      {isLoadingDashboard?<Loader/>:null}
      {isErrorDashboard?<Error mymessage={dataDashboard.message}/>:null}

      <Grid container spacing={3} >
        <Grid xs={12} sm={6} md={3}>
          <AppWidgetSummary
            title="My Queries"
            total={totalqueries}
            color="success"
            icon={<img alt="icon" src="/assets/icons/glass/ic_query.png" />}
          />
         
  
        </Grid>

        <Grid xs={12} sm={6} md={3} ref={ref1}>
          <AppWidgetSummary
            title="Identified Claims"
            total={totalswitchclaims}
            color="info"
            icon={<img alt="icon" src="/assets/icons/glass/ic_request.png" />}
          />
        </Grid>

        <Grid xs={12} sm={6} md={3} ref={ref2}>
          <AppWidgetSummary
            title="FAQs"
            total={0}
            color="warning"
            icon={<img alt="icon" src="/assets/icons/glass/ic_claim.png" />}
          />
          
        </Grid>

        <Grid xs={12} sm={6} md={3} ref={ref3}>
          <AppWidgetSummary
            title="Tips"
            total={totalclaims}
            color="error"
            icon={<img alt="icon" src="/assets/icons/glass/ic_tips.png" />}
          />
        </Grid>

        <Grid xs={12} md={6} lg={8}>
          <AppConversionRates
            title="Best Guess"
            subheader="where Benefits are paid from"
            count={0}
            chart={{
              series: [
                { label: 'Correct', value: 1 },
                { label: 'Incorrect', value: 0 },
                { label: 'Possibly Correct', value: 0 },
                { label: 'Possibly Incorrect', value: 0 },
              ],
            }}
          />         
        </Grid>

        <Grid xs={12} md={6} lg={4}>
          <AppCurrentVisits
            title="Diagnosis Groups"
            count={graph2.length}
            chart={{
              series: graph2,
            }}
          />
        </Grid>

        <Grid xs={12} md={6} lg={8}>
          <AppNewsUpdate
            title="Identified Claims"
            list={switchclaims}
            count={totalswitchclaims}
          />
           {!open && <Tour open={open} onClose={() => setOpen(false)} steps={steps} />}
        </Grid>

        <Grid xs={12} md={6} lg={4}>
          <AppOrderTimeline
            title="My Timeline"
            list={trail}
          />
        </Grid>
       
      </Grid>
    </Container>
  );
}
