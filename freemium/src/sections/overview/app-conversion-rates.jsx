import { Button } from 'antd';
import PropTypes from 'prop-types';

import Box from '@mui/material/Box';
import Card from '@mui/material/Card';
import CardHeader from '@mui/material/CardHeader';

import { useRouter } from 'src/routes/hooks';

import { fNumber } from 'src/utils/format-number';

import Chart, { useChart } from 'src/components/chart';

import FormUpload from './upload';

// ----------------------------------------------------------------------

export default function AppConversionRates({ title, subheader, chart, count,posscorrect,possincorrect, ...other }) {
  const router = useRouter();
  const { colors, series, options } = chart;

  const chartSeries = series.map((i) => i.value);

  const chartOptions = useChart({
    colors,
    tooltip: {
      marker: { show: false },
      y: {
        formatter: (value) => fNumber(value),
        title: {
          formatter: () => '',
        },
      },
    },
    plotOptions: {
      bar: {
        horizontal: false,
        barHeight: '28%',
        borderRadius: 2,
      },
    },
    xaxis: {
      categories: series.map((i) => i.label),
    },
    ...options,
  });

  const handleFilesBtn = () =>{
    router.push(`/documents`);
  }
  const handleGraphBtn = (poss=1) =>{
    router.push(`/switch-claims?poss=${poss}`);
  }

  return (
    <Card {...other}>
      <CardHeader title={title} subheader={subheader} />
      
      <Box sx={{ mx: 3 }}>
      {count>0?
      <>
      <Button type="dashed" onClick={handleFilesBtn} style={{marginTop:10}}>Uploaded Files</Button> 
      <Button type="dashed" onClick={() => handleGraphBtn(1)} style={{marginTop:10, marginLeft:10,borderColor: '#00A76F',color: '#00A76F'}}>({posscorrect.length}) Possibly Correct</Button> 
      <Button type="dashed" onClick={() => handleGraphBtn(2)} style={{marginTop:10, marginLeft:10,borderColor: '#00A76F',color: '#00A76F'}}>({possincorrect.length}) Possibly Incorrect</Button>
        <Chart
          dir="ltr"
          type="bar"
          series={[{ data: chartSeries }]}
          options={chartOptions}
          width="100%"
          height={364}
        /></>: <><p className="ant-upload-hint">Insuffient Information</p><FormUpload/></>
        }
      </Box>
    </Card>
  );
}

AppConversionRates.propTypes = {
  chart: PropTypes.object,
  count: PropTypes.number,
  posscorrect: PropTypes.any,
  possincorrect: PropTypes.any,
  subheader: PropTypes.string,
  title: PropTypes.string,
};
