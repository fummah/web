import SvgColor from 'src/components/svg-color';

// ----------------------------------------------------------------------

const icon = (name) => (
  <SvgColor src={`/assets/icons/navbar/${name}.svg`} sx={{ width: 1, height: 1 }} />
);

const navConfig = [
  {
    title: 'dashboard',
    path: '/',
    icon: icon('ic_analytics'),
  },
  {
    title: 'queries',
    path: '/query',
    icon: icon('ic_document'),
  }, 
  {
    title: 'Identified Claims',
    path: '/switch-claims',
    icon: icon('ic_request'),
  },
  {
    title: 'FAQs',
    path: '/faq',
    icon: icon('ic_faq'),
  },
  {
    title: 'Blog',
    path: '/blog',
    icon: icon('ic_blog'),
  },
   {
    title: 'profile',
    path: '/profile',
    icon: icon('ic_user'),
  },

];

export default navConfig;
