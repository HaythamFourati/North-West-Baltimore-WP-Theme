# North West Baltimore WordPress Theme

A modern, responsive WordPress theme designed specifically for the North West Baltimore community website. This theme features a business directory system with Google Places API integration, advanced search capabilities, and a modern UI built with Tailwind CSS.

## 🚀 Features

### Business Directory
- Custom post type for business listings
- Google Business Profile integration
- Automatic business information population from Google Places API
- Business categories and city taxonomies
- Business hours display
- Photo gallery support
- Featured business highlighting
- Google Reviews integration

### Modern UI/UX
- Responsive design using Tailwind CSS
- Modern image slider with touch support
- Clean, accessible navigation
- Mobile-optimized menus
- Category and location-based filtering
- Advanced search functionality

### Technical Features
- Built with WordPress best practices
- React.js components for dynamic features
- Tailwind CSS for styling
- Google Places API integration
- Custom meta boxes for business information
- Optimized database queries
- SEO-friendly structure

## 📋 Prerequisites

Before installing this theme, ensure you have:

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Node.js 14.0 or higher (for development)
- Google Places API key
- npm or yarn package manager

## 🔧 Installation

1. **Theme Installation**
   ```bash
   # Clone the repository into your WordPress themes directory
   cd wp-content/themes/
   git clone [repository-url] North-West-Baltimore-WP-Theme
   ```

2. **Install Dependencies**
   ```bash
   cd North-West-Baltimore-WP-Theme
   npm install
   ```

3. **Build Assets**
   ```bash
   npm run build
   ```

4. **Activate the Theme**
   - Go to WordPress Admin Panel > Appearance > Themes
   - Activate "North West Baltimore"

5. **Configure Google Places API**
   - Go to WordPress Admin > Settings > Google Places API
   - Enter your Google Places API key
   - Save changes

## 🛠️ Development

### Build Commands
- `npm run build` - Build production assets
- `npm run dev` - Start development server with hot reloading
- `npm run watch` - Watch for file changes

### Directory Structure
```
North-West-Baltimore-WP-Theme/
├── build/                  # Compiled assets
├── src/                    # Source files
│   ├── assets/            # Images and other assets
│   ├── scripts/           # JavaScript modules
│   ├── index.js           # Main JavaScript entry
│   ├── index.css          # Main CSS entry
│   └── slider.js          # Slider component
├── template-parts/        # Reusable template parts
│   └── business/          # Business-related templates
├── *.php                  # Theme PHP files
└── style.css             # Theme stylesheet
```

### Custom Post Types and Taxonomies
- **Business Listings** (`business`)
  - Categories (`business_category`)
  - Cities (`business_city`)

### Meta Fields
- Business Address
- Phone Number
- Email Address
- Website URL
- Business Hours
- Google Place ID
- Featured Status

## 🎨 Customization

### Adding New Business Categories
1. Go to WordPress Admin > Business Listings > Categories
2. Add new categories as needed
3. Categories support hierarchical structure

### Modifying Business Hours Format
- Edit the default template in `functions.php`
- Format: "Day: HH:MM AM - HH:MM PM"

### Styling
- Theme uses Tailwind CSS
- Customize styles in `src/index.css`
- Build with `npm run build`

## 📱 Mobile Responsiveness

The theme is fully responsive with breakpoints:
- Mobile: < 640px
- Tablet: 640px - 1024px
- Desktop: > 1024px

## 🔍 SEO Considerations

- Semantic HTML structure
- Optimized meta descriptions
- Schema markup for businesses
- Clean URL structure
- Mobile-friendly design
- Optimized image handling

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## 📄 License

This theme is licensed under the GPL v2 or later.

## 📞 Support

For support, please:
1. Check the documentation
2. Create an issue in the repository
3. Contact the theme maintainer

## 🔄 Updates

Keep your theme up to date:
1. Back up your theme
2. Pull latest changes
3. Run `npm install` if dependencies change
4. Run `npm run build`
5. Test thoroughly

## 🔐 Security

- Keep WordPress core updated
- Keep API keys secure
- Regular backups
- Monitor error logs
