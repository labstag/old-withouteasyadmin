import { storiesOf } from '@storybook/html';
import Test from './index.html.twig';

storiesOf('Test', module)
  .add('Default', () =>
  Test({
      type: 'button',
      label: 'text'
    })
  );