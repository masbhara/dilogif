import { cva, type VariantProps } from 'class-variance-authority'

export { default as Button } from './Button.vue'
export { default as ButtonLoader } from './ButtonLoader.vue'
export { buttonTheme, getButtonClasses, getSpinnerClasses } from './theme'

export const buttonVariants = cva(
  'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*=\'size-\'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
  {
    variants: {
      variant: {
        default:
          'shadow-xs focus-visible:ring-primary-500/50',
        destructive:
          'bg-danger-600 text-white shadow-xs hover:bg-danger-700 focus-visible:ring-danger-500/50',
        outline:
          'border border-primary-300 bg-transparent shadow-xs hover:bg-primary-50 hover:text-primary-700 focus-visible:ring-primary-300/50 dark:border-secondary-700 dark:text-secondary-300 dark:bg-secondary-800 dark:hover:bg-secondary-700 dark:hover:text-white',
        secondary:
          'bg-secondary-600 text-white shadow-xs hover:bg-secondary-700 focus-visible:ring-secondary-500/50',
        ghost:
          'hover:bg-primary-100 text-primary-600 hover:text-primary-700 focus-visible:ring-primary-200/50 dark:text-primary-400 dark:hover:bg-primary-950/50 dark:hover:text-primary-300 dark:focus-visible:ring-primary-800/50',
        link: 'text-primary-600 underline-offset-4 hover:underline focus-visible:ring-primary-200/50',
        success:
          'bg-success-600 text-white shadow-xs hover:bg-success-700 focus-visible:ring-success-500/50',
        warning:
          'bg-warning-500 text-white shadow-xs hover:bg-warning-600 focus-visible:ring-warning-400/50',
      },
      size: {
        default: 'h-9 px-4 py-2 has-[>svg]:px-3',
        sm: 'h-8 rounded-md gap-1.5 px-3 has-[>svg]:px-2.5',
        lg: 'h-10 rounded-md px-6 has-[>svg]:px-4',
        icon: 'size-9',
      },
    },
    defaultVariants: {
      variant: 'default',
      size: 'default',
    },
  },
)

export type ButtonVariants = VariantProps<typeof buttonVariants>
