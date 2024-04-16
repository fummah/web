import React, { useState,useEffect } from 'react';

import Card from '@mui/material/Card';
import Stack from '@mui/material/Stack';
import { styled } from '@mui/material/styles';
import Container from '@mui/material/Container';
import Typography from '@mui/material/Typography';
import MuiAccordion from '@mui/material/Accordion';
import MuiAccordionSummary from '@mui/material/AccordionSummary';
import MuiAccordionDetails from '@mui/material/AccordionDetails';
import ArrowForwardIosSharpIcon from '@mui/icons-material/ArrowForwardIosSharp';

import useAxiosFetch from 'src/hooks/use-axios';

import Scrollbar from 'src/components/scrollbar';
import Error from 'src/components/response/error';
import Loader from 'src/components/response/loader';

// ----------------------------------------------------------------------
const Accordion = styled((props) => (
  <MuiAccordion disableGutters elevation={0} square {...props} />
))(({ theme }) => ({
  border: `1px solid ${theme.palette.divider}`,
  '&:not(:last-child)': {
    borderBottom: 0,
  },
  '&::before': {
    display: 'none',
  },
}));

const AccordionSummary = styled((props) => (
  <MuiAccordionSummary
    expandIcon={<ArrowForwardIosSharpIcon sx={{ fontSize: '0.9rem' }} />}
    {...props}
  />
))(({ theme }) => ({
  backgroundColor:
    theme.palette.mode === 'dark'
      ? 'rgba(255, 255, 255, .05)'
      : 'rgba(0, 0, 0, .03)',
  flexDirection: 'row-reverse',
  '& .MuiAccordionSummary-expandIconWrapper.Mui-expanded': {
    transform: 'rotate(90deg)',
  },
  '& .MuiAccordionSummary-content': {
    marginLeft: theme.spacing(1),
  },
}));

const AccordionDetails = styled(MuiAccordionDetails)(({ theme }) => ({
  padding: theme.spacing(2),
  borderTop: '1px solid rgba(0, 0, 0, .125)',
}));

export default function FaqPage() {
 const [expanded, setExpanded] = useState('');
 const [faqs,setFaqs] = useState([]);
 const { isLoading,isError, data,statusCode } = useAxiosFetch('getfaqs','GET', {});

 useEffect(() => {
 
  if(data && statusCode===200)
  {    
    setFaqs(data.faqs);
    
  }   
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [data]); 

  const handleChange = (panel) => (event, newExpanded) => {
    setExpanded(newExpanded ? panel : false);
  };
  return (
    <Container>
        {isLoading?<Loader/>:null}
      {isError?<Error mymessage={data.message}/>:null}
      <Stack direction="row" alignItems="center" justifyContent="space-between" mb={5}>
        <Typography variant="h4">Frequent Asked Questions</Typography>

      </Stack>

      <Card>
      

        <Scrollbar>
        {faqs.length>0?
          <div>
            {
            faqs.map((faq,index) =>
      <Accordion expanded={expanded === index} key={index} onChange={handleChange(index)}>
        <AccordionSummary aria-controls="panel1d-content" id="panel1d-header">
          <Typography>{faq.title}</Typography>
        </AccordionSummary>
        <AccordionDetails>
          <Typography>
          {faq.description}
          </Typography>
        </AccordionDetails>
      </Accordion>
      )}
    </div>:<Typography align='center' style={{color:"red",width:"100%"}}>No Frequently Asked Questions</Typography>}
        </Scrollbar>

      </Card>
    </Container>
  );
}
