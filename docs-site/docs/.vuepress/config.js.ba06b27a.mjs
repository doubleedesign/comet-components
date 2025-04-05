// docs/.vuepress/config.js
import { defaultTheme } from "@vuepress/theme-default";
import { defineUserConfig } from "vuepress/cli";
import { viteBundler } from "@vuepress/bundler-vite";
import path from "path";
import fs from "fs";
import Case from "case";
import { markdownTabPlugin } from "@vuepress/plugin-markdown-tab";
import { markdownExtPlugin } from "@vuepress/plugin-markdown-ext";
import { prismjsPlugin } from "@vuepress/plugin-prismjs";
import { searchPlugin } from "@vuepress/plugin-search";
import { rightAnchorPlugin } from "vuepress-plugin-right-anchor";
var __vite_injected_original_dirname = "C:/Users/LeesaWard/PHPStormProjects/comet-components/docs-site/docs/.vuepress";
var docsDir = path.resolve(__vite_injected_original_dirname, "../");
var config_default = defineUserConfig({
  lang: "en-AU",
  title: "Comet Components",
  description: "A front-end user interface library for PHP-driven websites",
  extend: "@vuepress/theme-default",
  theme: defaultTheme({
    logo: "/comet.png",
    repo: "doubleedesign/comet-components",
    repoLabel: "GitHub",
    navbar: [
      "/",
      {
        text: "Introduction",
        link: "/intro.html"
      },
      {
        text: "Installation",
        link: "/installation/wordpress.html"
      },
      {
        text: "Usage",
        link: "/usage/overview.html"
      },
      {
        text: "Development",
        link: "/development/overview.html"
      }
    ],
    sidebar: [
      {
        text: "Introduction",
        link: "/intro.html"
      },
      ...generateSidebar({ excludeFolders: ["about"] }),
      {
        text: "Troubleshooting",
        link: "/troubleshooting.html"
      },
      {
        text: "About",
        link: "/about.html",
        collapsible: true,
        children: getSectionChildren("about")
      }
    ],
    sidebarDepth: 0,
    // don't put page headings in the sidebar
    markdown: {
      lineNumbers: true
    }
  }),
  plugins: [
    markdownTabPlugin({
      tabs: true
    }),
    markdownExtPlugin({
      gfm: true,
      footnote: true
    }),
    prismjsPlugin({
      theme: "coldark-dark",
      preloadLanguages: ["php", "html", "css", "scss", "js", "json", "bash", "powershell"]
    }),
    searchPlugin(),
    rightAnchorPlugin({
      showDepth: 3,
      expand: {
        trigger: "click",
        clickModeDefaultOpen: true
      },
      // Optional: control display on specific pages
      ignore: [
        // Add paths you want to exclude
      ]
    })
  ],
  bundler: viteBundler(),
  dest: "../docs",
  base: "/docs/",
  head: [
    ["link", { rel: "icon", type: "image/png", sizes: "32x32", href: "/comet.png" }]
  ]
});
function generateSidebar({ excludeFolders }) {
  const preferredOrder = [
    "Installation",
    "Usage",
    "Development (Core)",
    "Technical Deep Dives",
    "New Implementations",
    "Local Dev Deep Dives"
  ];
  const items = [];
  const files = fs.readdirSync(docsDir, { withFileTypes: true });
  files.forEach((file) => {
    if (file.isDirectory() && file.name !== ".vuepress") {
      const folderName = file.name;
      const readmePath = path.join(docsDir, folderName, "README.md");
      const hasReadme = fs.existsSync(readmePath);
      let sectionTitle;
      if (folderName === "development-core") {
        sectionTitle = "Development (Core)";
      } else if (folderName === "development-new") {
        sectionTitle = "New Implementations";
      } else {
        sectionTitle = Case.title(folderName).replace("Js", "JS").replace("Php", "PHP");
        if (hasReadme) {
          const extractedTitle = extractTitleFromMarkdown(readmePath);
          if (extractedTitle) {
            sectionTitle = extractedTitle;
          }
        }
      }
      items.push({
        text: sectionTitle,
        link: hasReadme ? `/${folderName}/` : void 0,
        collapsible: true,
        children: getSectionChildren(folderName)
      });
    }
  });
  return items.sort((a, b) => {
    const aIndex = preferredOrder.indexOf(a.text);
    const bIndex = preferredOrder.indexOf(b.text);
    if (aIndex === -1 && bIndex === -1) {
      return a.text.localeCompare(b.text);
    }
    if (aIndex === -1) {
      return 1;
    }
    if (bIndex === -1) {
      return -1;
    }
    return aIndex - bIndex;
  }).filter((item) => {
    if (excludeFolders) {
      return !excludeFolders.includes(Case.title(item.text));
    }
    return true;
  });
}
function getSectionChildren(folderName) {
  const folderPath = path.join(docsDir, folderName);
  if (!fs.existsSync(folderPath) || !fs.statSync(folderPath).isDirectory()) {
    return [];
  }
  const children = [];
  const items = fs.readdirSync(folderPath, { withFileTypes: true });
  const filesWithMetadata = items.filter((item) => item.isFile() && item.name.endsWith(".md")).map((file) => {
    const name = file.name.replace(".md", "");
    if (name !== "README") {
      const filePath = path.join(folderPath, file.name);
      const title = extractTitleFromMarkdown(filePath) ?? Case.title(name);
      const position = extractPagePositionFromMarkdown(filePath);
      return {
        text: title,
        link: `/${folderName}/${name}`,
        position: position !== null ? parseInt(position, 10) : null
      };
    }
    return null;
  }).filter(Boolean);
  filesWithMetadata.sort((a, b) => {
    if (a.position !== null && b.position !== null) {
      return a.position - b.position;
    }
    if (a.position !== null) {
      return -1;
    }
    if (b.position !== null) {
      return 1;
    }
    return a.text.localeCompare(b.text);
  });
  children.push(...filesWithMetadata);
  items.filter((item) => item.isDirectory()).forEach((subfolder) => {
    const subfolderName = subfolder.name;
    const subfolderPath = path.join(folderPath, subfolderName);
    const subfolderItems = [];
    const subfolderFilesWithMetadata = fs.readdirSync(subfolderPath, { withFileTypes: true }).filter((subItem) => subItem.isFile() && subItem.name.endsWith(".md")).map((subFile) => {
      const name = subFile.name.replace(".md", "");
      if (name !== "README") {
        const filePath = path.join(subfolderPath, subFile.name);
        const title = extractTitleFromMarkdown(filePath) ?? Case.title(name);
        const position = extractPagePositionFromMarkdown(filePath);
        return {
          text: title,
          link: `/${folderName}/${subfolderName}/${name}`,
          position: position !== null ? parseInt(position, 10) : null
        };
      }
      return null;
    }).filter(Boolean);
    subfolderFilesWithMetadata.sort((a, b) => {
      if (a.position !== null && b.position !== null) {
        return a.position - b.position;
      }
      if (a.position !== null) {
        return -1;
      }
      if (b.position !== null) {
        return 1;
      }
      return a.text.localeCompare(b.text);
    });
    subfolderItems.push(...subfolderFilesWithMetadata);
    const subfolderReadmePath = path.join(subfolderPath, "README.md");
    let subfolderTitle = Case.title(subfolderName).replace("Js", "JS").replace("Php", "PHP");
    if (fs.existsSync(subfolderReadmePath)) {
      const extractedTitle = extractTitleFromMarkdown(subfolderReadmePath);
      if (extractedTitle) {
        subfolderTitle = extractedTitle;
      }
    }
    if (subfolderItems.length > 0) {
      children.push({
        text: subfolderTitle,
        collapsible: true,
        children: subfolderItems
      });
    }
  });
  return children.sort((a, b) => {
    if (!a.children && b.children) return -1;
    if (a.children && !b.children) return 1;
    if (!a.children && !b.children) {
      return 0;
    }
    return a.text.localeCompare(b.text);
  });
}
function extractTitleFromMarkdown(filePath) {
  try {
    const content = fs.readFileSync(filePath, "utf8");
    const titleMatch = content.match(/^title:\s*(.+)$/m) || content.match(/^#\s+(.+)$/m);
    if (titleMatch && titleMatch[1]) {
      return titleMatch[1].trim();
    }
    return null;
  } catch (error) {
    console.error(`Error reading file ${filePath}:`, error);
    return null;
  }
}
function extractPagePositionFromMarkdown(filePath) {
  try {
    const content = fs.readFileSync(filePath, "utf8");
    const positionMatch = content.match(/^position:\s*(.+)$/m);
    if (positionMatch && positionMatch[1]) {
      return positionMatch[1].trim();
    }
    return null;
  } catch (error) {
    console.error(`Error reading file ${filePath}:`, error);
    return null;
  }
}
export {
  config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiZG9jcy8udnVlcHJlc3MvY29uZmlnLmpzIl0sCiAgInNvdXJjZXNDb250ZW50IjogWyJjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZGlybmFtZSA9IFwiQzovVXNlcnMvTGVlc2FXYXJkL1BIUFN0b3JtUHJvamVjdHMvY29tZXQtY29tcG9uZW50cy9kb2NzLXNpdGUvZG9jcy8udnVlcHJlc3NcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXExlZXNhV2FyZFxcXFxQSFBTdG9ybVByb2plY3RzXFxcXGNvbWV0LWNvbXBvbmVudHNcXFxcZG9jcy1zaXRlXFxcXGRvY3NcXFxcLnZ1ZXByZXNzXFxcXGNvbmZpZy5qc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vQzovVXNlcnMvTGVlc2FXYXJkL1BIUFN0b3JtUHJvamVjdHMvY29tZXQtY29tcG9uZW50cy9kb2NzLXNpdGUvZG9jcy8udnVlcHJlc3MvY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmYXVsdFRoZW1lIH0gZnJvbSAnQHZ1ZXByZXNzL3RoZW1lLWRlZmF1bHQnO1xyXG5pbXBvcnQgeyBkZWZpbmVVc2VyQ29uZmlnIH0gZnJvbSAndnVlcHJlc3MvY2xpJztcclxuaW1wb3J0IHsgdml0ZUJ1bmRsZXIgfSBmcm9tICdAdnVlcHJlc3MvYnVuZGxlci12aXRlJztcclxuaW1wb3J0IHBhdGggZnJvbSAncGF0aCc7XHJcbmltcG9ydCBmcyBmcm9tICdmcyc7XHJcbmltcG9ydCBDYXNlIGZyb20gJ2Nhc2UnO1xyXG5pbXBvcnQgeyBtYXJrZG93blRhYlBsdWdpbiB9IGZyb20gJ0B2dWVwcmVzcy9wbHVnaW4tbWFya2Rvd24tdGFiJztcclxuaW1wb3J0IHsgbWFya2Rvd25FeHRQbHVnaW4gfSBmcm9tICdAdnVlcHJlc3MvcGx1Z2luLW1hcmtkb3duLWV4dCc7XHJcbmltcG9ydCB7IHByaXNtanNQbHVnaW4gfSBmcm9tICdAdnVlcHJlc3MvcGx1Z2luLXByaXNtanMnO1xyXG5pbXBvcnQgeyBzZWFyY2hQbHVnaW4gfSBmcm9tICdAdnVlcHJlc3MvcGx1Z2luLXNlYXJjaCc7XHJcbmltcG9ydCB7IHJpZ2h0QW5jaG9yUGx1Z2luIH0gZnJvbSAndnVlcHJlc3MtcGx1Z2luLXJpZ2h0LWFuY2hvcic7XHJcblxyXG5jb25zdCBkb2NzRGlyID0gcGF0aC5yZXNvbHZlKF9fZGlybmFtZSwgJy4uLycpO1xyXG5cclxuZXhwb3J0IGRlZmF1bHQgZGVmaW5lVXNlckNvbmZpZyh7XHJcblx0bGFuZzogJ2VuLUFVJyxcclxuXHJcblx0dGl0bGU6ICdDb21ldCBDb21wb25lbnRzJyxcclxuXHRkZXNjcmlwdGlvbjogJ0EgZnJvbnQtZW5kIHVzZXIgaW50ZXJmYWNlIGxpYnJhcnkgZm9yIFBIUC1kcml2ZW4gd2Vic2l0ZXMnLFxyXG5cclxuXHRleHRlbmQ6ICdAdnVlcHJlc3MvdGhlbWUtZGVmYXVsdCcsXHJcblx0dGhlbWU6IGRlZmF1bHRUaGVtZSh7XHJcblx0XHRsb2dvOiAnL2NvbWV0LnBuZycsXHJcblx0XHRyZXBvOiAnZG91YmxlZWRlc2lnbi9jb21ldC1jb21wb25lbnRzJyxcclxuXHRcdHJlcG9MYWJlbDogJ0dpdEh1YicsXHJcblx0XHRuYXZiYXI6IFtcclxuXHRcdFx0Jy8nLFxyXG5cdFx0XHR7XHJcblx0XHRcdFx0dGV4dDogJ0ludHJvZHVjdGlvbicsXHJcblx0XHRcdFx0bGluazogJy9pbnRyby5odG1sJyxcclxuXHRcdFx0fSxcclxuXHRcdFx0e1xyXG5cdFx0XHRcdHRleHQ6ICdJbnN0YWxsYXRpb24nLFxyXG5cdFx0XHRcdGxpbms6ICcvaW5zdGFsbGF0aW9uL3dvcmRwcmVzcy5odG1sJ1xyXG5cdFx0XHR9LFxyXG5cdFx0XHR7XHJcblx0XHRcdFx0dGV4dDogJ1VzYWdlJyxcclxuXHRcdFx0XHRsaW5rOiAnL3VzYWdlL292ZXJ2aWV3Lmh0bWwnXHJcblx0XHRcdH0sXHJcblx0XHRcdHtcclxuXHRcdFx0XHR0ZXh0OiAnRGV2ZWxvcG1lbnQnLFxyXG5cdFx0XHRcdGxpbms6ICcvZGV2ZWxvcG1lbnQvb3ZlcnZpZXcuaHRtbCcsXHJcblx0XHRcdH1cclxuXHRcdF0sXHJcblx0XHRzaWRlYmFyOiBbXHJcblx0XHRcdHtcclxuXHRcdFx0XHR0ZXh0OiAnSW50cm9kdWN0aW9uJyxcclxuXHRcdFx0XHRsaW5rOiAnL2ludHJvLmh0bWwnLFxyXG5cdFx0XHR9LFxyXG5cdFx0XHQuLi5nZW5lcmF0ZVNpZGViYXIoeyBleGNsdWRlRm9sZGVyczogWydhYm91dCddIH0pLFxyXG5cdFx0XHR7XHJcblx0XHRcdFx0dGV4dDogJ1Ryb3VibGVzaG9vdGluZycsXHJcblx0XHRcdFx0bGluazogJy90cm91Ymxlc2hvb3RpbmcuaHRtbCcsXHJcblx0XHRcdH0sXHJcblx0XHRcdHtcclxuXHRcdFx0XHR0ZXh0OiAnQWJvdXQnLFxyXG5cdFx0XHRcdGxpbms6ICcvYWJvdXQuaHRtbCcsXHJcblx0XHRcdFx0Y29sbGFwc2libGU6IHRydWUsXHJcblx0XHRcdFx0Y2hpbGRyZW46IGdldFNlY3Rpb25DaGlsZHJlbignYWJvdXQnKVxyXG5cdFx0XHR9XHJcblx0XHRdLFxyXG5cdFx0c2lkZWJhckRlcHRoOiAwLCAvLyBkb24ndCBwdXQgcGFnZSBoZWFkaW5ncyBpbiB0aGUgc2lkZWJhclxyXG5cdFx0bWFya2Rvd246IHtcclxuXHRcdFx0bGluZU51bWJlcnM6IHRydWUsXHJcblx0XHR9LFxyXG5cdH0pLFxyXG5cclxuXHRwbHVnaW5zOiBbXHJcblx0XHRtYXJrZG93blRhYlBsdWdpbih7XHJcblx0XHRcdHRhYnM6IHRydWUsXHJcblx0XHR9KSxcclxuXHRcdG1hcmtkb3duRXh0UGx1Z2luKHtcclxuXHRcdFx0Z2ZtOiB0cnVlLFxyXG5cdFx0XHRmb290bm90ZTogdHJ1ZVxyXG5cdFx0fSksXHJcblx0XHRwcmlzbWpzUGx1Z2luKHtcclxuXHRcdFx0dGhlbWU6ICdjb2xkYXJrLWRhcmsnLFxyXG5cdFx0XHRwcmVsb2FkTGFuZ3VhZ2VzOiBbJ3BocCcsICdodG1sJywgJ2NzcycsICdzY3NzJywgJ2pzJywgJ2pzb24nLCAnYmFzaCcsICdwb3dlcnNoZWxsJ10sXHJcblx0XHR9KSxcclxuXHRcdHNlYXJjaFBsdWdpbigpLFxyXG5cdFx0cmlnaHRBbmNob3JQbHVnaW4oe1xyXG5cdFx0XHRzaG93RGVwdGg6IDMsXHJcblx0XHRcdGV4cGFuZDoge1xyXG5cdFx0XHRcdHRyaWdnZXI6ICdjbGljaycsXHJcblx0XHRcdFx0Y2xpY2tNb2RlRGVmYXVsdE9wZW46IHRydWVcclxuXHRcdFx0fSxcclxuXHRcdFx0Ly8gT3B0aW9uYWw6IGNvbnRyb2wgZGlzcGxheSBvbiBzcGVjaWZpYyBwYWdlc1xyXG5cdFx0XHRpZ25vcmU6IFtcclxuXHRcdFx0XHQvLyBBZGQgcGF0aHMgeW91IHdhbnQgdG8gZXhjbHVkZVxyXG5cdFx0XHRdXHJcblx0XHR9KVxyXG5cdF0sXHJcblxyXG5cdGJ1bmRsZXI6IHZpdGVCdW5kbGVyKCksXHJcblx0ZGVzdDogJy4uL2RvY3MnLFxyXG5cdGJhc2U6ICcvZG9jcy8nLFxyXG5cclxuXHRoZWFkOiBbXHJcblx0XHRbJ2xpbmsnLCB7IHJlbDogJ2ljb24nLCB0eXBlOiAnaW1hZ2UvcG5nJywgc2l6ZXM6ICczMngzMicsIGhyZWY6ICcvY29tZXQucG5nJyB9XSxcclxuXHRdLFxyXG59KTtcclxuXHJcbi8vIEdlbmVyYXRlIHN0cnVjdHVyZWQgc2lkZWJhciBpdGVtc1xyXG5mdW5jdGlvbiBnZW5lcmF0ZVNpZGViYXIoeyBleGNsdWRlRm9sZGVycyB9KSB7XHJcblx0Y29uc3QgcHJlZmVycmVkT3JkZXIgPSBbXHJcblx0XHQnSW5zdGFsbGF0aW9uJyxcclxuXHRcdCdVc2FnZScsXHJcblx0XHQnRGV2ZWxvcG1lbnQgKENvcmUpJyxcclxuXHRcdCdUZWNobmljYWwgRGVlcCBEaXZlcycsXHJcblx0XHQnTmV3IEltcGxlbWVudGF0aW9ucycsXHJcblx0XHQnTG9jYWwgRGV2IERlZXAgRGl2ZXMnLFxyXG5cdF07XHJcblx0Y29uc3QgaXRlbXMgPSBbXTtcclxuXHRjb25zdCBmaWxlcyA9IGZzLnJlYWRkaXJTeW5jKGRvY3NEaXIsIHsgd2l0aEZpbGVUeXBlczogdHJ1ZSB9KTtcclxuXHJcblx0ZmlsZXMuZm9yRWFjaCgoZmlsZSkgPT4ge1xyXG5cdFx0aWYgKGZpbGUuaXNEaXJlY3RvcnkoKSAmJiBmaWxlLm5hbWUgIT09ICcudnVlcHJlc3MnKSB7XHJcblx0XHRcdGNvbnN0IGZvbGRlck5hbWUgPSBmaWxlLm5hbWU7XHJcblx0XHRcdC8vIENoZWNrIGlmIHRoZXJlJ3MgYSBSRUFETUUubWQgZmlsZSBmb3IgdGhlIG1haW4gc2VjdGlvblxyXG5cdFx0XHRjb25zdCByZWFkbWVQYXRoID0gcGF0aC5qb2luKGRvY3NEaXIsIGZvbGRlck5hbWUsICdSRUFETUUubWQnKTtcclxuXHRcdFx0Y29uc3QgaGFzUmVhZG1lID0gZnMuZXhpc3RzU3luYyhyZWFkbWVQYXRoKTtcclxuXHRcdFx0bGV0IHNlY3Rpb25UaXRsZTtcclxuXHJcblx0XHRcdC8vIEhhY2tpbHkgbWFudWFsbHkgbmFtZSB0aGUgaXRlbXMgZm9yIGNlcnRhaW4gZm9sZGVyc1xyXG5cdFx0XHRpZihmb2xkZXJOYW1lID09PSAnZGV2ZWxvcG1lbnQtY29yZScpIHtcclxuXHRcdFx0XHRzZWN0aW9uVGl0bGUgPSAnRGV2ZWxvcG1lbnQgKENvcmUpJztcclxuXHRcdFx0fVxyXG5cdFx0XHRlbHNlIGlmKGZvbGRlck5hbWUgPT09ICdkZXZlbG9wbWVudC1uZXcnKSB7XHJcblx0XHRcdFx0c2VjdGlvblRpdGxlID0gJ05ldyBJbXBsZW1lbnRhdGlvbnMnO1xyXG5cdFx0XHR9XHJcblx0XHRcdGVsc2Uge1xyXG5cdFx0XHRcdC8vIFRyeSB0byBleHRyYWN0IHRpdGxlIGZyb20gUkVBRE1FIGlmIGl0IGV4aXN0c1xyXG5cdFx0XHRcdHNlY3Rpb25UaXRsZSA9IENhc2UudGl0bGUoZm9sZGVyTmFtZSkucmVwbGFjZSgnSnMnLCAnSlMnKS5yZXBsYWNlKCdQaHAnLCAnUEhQJyk7XHJcblx0XHRcdFx0aWYgKGhhc1JlYWRtZSkge1xyXG5cdFx0XHRcdFx0Y29uc3QgZXh0cmFjdGVkVGl0bGUgPSBleHRyYWN0VGl0bGVGcm9tTWFya2Rvd24ocmVhZG1lUGF0aCk7XHJcblx0XHRcdFx0XHRpZiAoZXh0cmFjdGVkVGl0bGUpIHtcclxuXHRcdFx0XHRcdFx0c2VjdGlvblRpdGxlID0gZXh0cmFjdGVkVGl0bGU7XHJcblx0XHRcdFx0XHR9XHJcblx0XHRcdFx0fVxyXG5cdFx0XHR9XHJcblxyXG5cdFx0XHRpdGVtcy5wdXNoKHtcclxuXHRcdFx0XHR0ZXh0OiBzZWN0aW9uVGl0bGUsXHJcblx0XHRcdFx0bGluazogaGFzUmVhZG1lID8gYC8ke2ZvbGRlck5hbWV9L2AgOiB1bmRlZmluZWQsXHJcblx0XHRcdFx0Y29sbGFwc2libGU6IHRydWUsXHJcblx0XHRcdFx0Y2hpbGRyZW46IGdldFNlY3Rpb25DaGlsZHJlbihmb2xkZXJOYW1lKVxyXG5cdFx0XHR9KTtcclxuXHRcdH1cclxuXHR9KTtcclxuXHJcblx0Ly8gU29ydCBhY2NvcmRpbmcgdG8gcHJlZmVycmVkIG9yZGVyXHJcblx0cmV0dXJuIGl0ZW1zLnNvcnQoKGEsIGIpID0+IHtcclxuXHRcdGNvbnN0IGFJbmRleCA9IHByZWZlcnJlZE9yZGVyLmluZGV4T2YoYS50ZXh0KTtcclxuXHRcdGNvbnN0IGJJbmRleCA9IHByZWZlcnJlZE9yZGVyLmluZGV4T2YoYi50ZXh0KTtcclxuXHRcdGlmIChhSW5kZXggPT09IC0xICYmIGJJbmRleCA9PT0gLTEpIHtcclxuXHRcdFx0cmV0dXJuIGEudGV4dC5sb2NhbGVDb21wYXJlKGIudGV4dCk7XHJcblx0XHR9XHJcblx0XHRpZiAoYUluZGV4ID09PSAtMSkge1xyXG5cdFx0XHRyZXR1cm4gMTtcclxuXHRcdH1cclxuXHRcdGlmIChiSW5kZXggPT09IC0xKSB7XHJcblx0XHRcdHJldHVybiAtMTtcclxuXHRcdH1cclxuXHJcblx0XHRyZXR1cm4gYUluZGV4IC0gYkluZGV4O1xyXG5cdH0pLmZpbHRlcigoaXRlbSA9PiB7XHJcblx0XHQvLyBGaWx0ZXIgb3V0IGV4Y2x1ZGVkIGZvbGRlcnNcclxuXHRcdGlmIChleGNsdWRlRm9sZGVycykge1xyXG5cdFx0XHRyZXR1cm4gIWV4Y2x1ZGVGb2xkZXJzLmluY2x1ZGVzKENhc2UudGl0bGUoaXRlbS50ZXh0KSk7XHJcblx0XHR9XHJcblxyXG5cdFx0cmV0dXJuIHRydWU7XHJcblx0fSkpO1xyXG59XHJcblxyXG4vLyBHZXQgdGhlIGNoaWxkIHBhZ2VzIGZvciBhIHNwZWNpZmljIHNlY3Rpb24gZm9sZGVyLCBpbmNsdWRpbmcgbmVzdGVkIHN1YmZvbGRlcnNcclxuZnVuY3Rpb24gZ2V0U2VjdGlvbkNoaWxkcmVuKGZvbGRlck5hbWUpIHtcclxuXHRjb25zdCBmb2xkZXJQYXRoID0gcGF0aC5qb2luKGRvY3NEaXIsIGZvbGRlck5hbWUpO1xyXG5cdC8vIENoZWNrIGlmIHRoZSBmb2xkZXIgZXhpc3RzXHJcblx0aWYgKCFmcy5leGlzdHNTeW5jKGZvbGRlclBhdGgpIHx8ICFmcy5zdGF0U3luYyhmb2xkZXJQYXRoKS5pc0RpcmVjdG9yeSgpKSB7XHJcblx0XHRyZXR1cm4gW107XHJcblx0fVxyXG5cclxuXHRjb25zdCBjaGlsZHJlbiA9IFtdO1xyXG5cclxuXHQvLyBHZXQgYWxsIGl0ZW1zIGluIHRoZSBkaXJlY3RvcnlcclxuXHRjb25zdCBpdGVtcyA9IGZzLnJlYWRkaXJTeW5jKGZvbGRlclBhdGgsIHsgd2l0aEZpbGVUeXBlczogdHJ1ZSB9KTtcclxuXHJcblx0Ly8gUHJvY2VzcyBmaWxlcyBmaXJzdFxyXG5cdGNvbnN0IGZpbGVzV2l0aE1ldGFkYXRhID0gaXRlbXNcclxuXHRcdC5maWx0ZXIoKGl0ZW0pID0+IGl0ZW0uaXNGaWxlKCkgJiYgaXRlbS5uYW1lLmVuZHNXaXRoKCcubWQnKSlcclxuXHRcdC5tYXAoKGZpbGUpID0+IHtcclxuXHRcdFx0Y29uc3QgbmFtZSA9IGZpbGUubmFtZS5yZXBsYWNlKCcubWQnLCAnJyk7XHJcblx0XHRcdGlmIChuYW1lICE9PSAnUkVBRE1FJykge1xyXG5cdFx0XHRcdGNvbnN0IGZpbGVQYXRoID0gcGF0aC5qb2luKGZvbGRlclBhdGgsIGZpbGUubmFtZSk7XHJcblx0XHRcdFx0Y29uc3QgdGl0bGUgPSBleHRyYWN0VGl0bGVGcm9tTWFya2Rvd24oZmlsZVBhdGgpID8/IENhc2UudGl0bGUobmFtZSk7XHJcblx0XHRcdFx0Y29uc3QgcG9zaXRpb24gPSBleHRyYWN0UGFnZVBvc2l0aW9uRnJvbU1hcmtkb3duKGZpbGVQYXRoKTtcclxuXHJcblx0XHRcdFx0cmV0dXJuIHtcclxuXHRcdFx0XHRcdHRleHQ6IHRpdGxlLFxyXG5cdFx0XHRcdFx0bGluazogYC8ke2ZvbGRlck5hbWV9LyR7bmFtZX1gLFxyXG5cdFx0XHRcdFx0cG9zaXRpb246IHBvc2l0aW9uICE9PSBudWxsID8gcGFyc2VJbnQocG9zaXRpb24sIDEwKSA6IG51bGxcclxuXHRcdFx0XHR9O1xyXG5cdFx0XHR9XHJcblxyXG5cdFx0XHRyZXR1cm4gbnVsbDtcclxuXHRcdH0pXHJcblx0XHQuZmlsdGVyKEJvb2xlYW4pO1xyXG5cclxuXHQvLyBTb3J0IGZpbGVzIGJ5IHBvc2l0aW9uIGZpcnN0LCB0aGVuIGJ5IHRpdGxlXHJcblx0ZmlsZXNXaXRoTWV0YWRhdGEuc29ydCgoYSwgYikgPT4ge1xyXG5cdFx0Ly8gSWYgYm90aCBoYXZlIHBvc2l0aW9ucywgc29ydCBudW1lcmljYWxseVxyXG5cdFx0aWYgKGEucG9zaXRpb24gIT09IG51bGwgJiYgYi5wb3NpdGlvbiAhPT0gbnVsbCkge1xyXG5cdFx0XHRyZXR1cm4gYS5wb3NpdGlvbiAtIGIucG9zaXRpb247XHJcblx0XHR9XHJcblx0XHQvLyBJZiBvbmx5IGEgaGFzIHBvc2l0aW9uLCBpdCBjb21lcyBmaXJzdFxyXG5cdFx0aWYgKGEucG9zaXRpb24gIT09IG51bGwpIHtcclxuXHRcdFx0cmV0dXJuIC0xO1xyXG5cdFx0fVxyXG5cdFx0Ly8gSWYgb25seSBiIGhhcyBwb3NpdGlvbiwgaXQgY29tZXMgZmlyc3RcclxuXHRcdGlmIChiLnBvc2l0aW9uICE9PSBudWxsKSB7XHJcblx0XHRcdHJldHVybiAxO1xyXG5cdFx0fVxyXG5cclxuXHRcdC8vIElmIG5laXRoZXIgaGFzIHBvc2l0aW9uLCBzb3J0IGFscGhhYmV0aWNhbGx5XHJcblx0XHRyZXR1cm4gYS50ZXh0LmxvY2FsZUNvbXBhcmUoYi50ZXh0KTtcclxuXHR9KTtcclxuXHJcblx0Ly8gQWRkIHRoZSBzb3J0ZWQgZmlsZXMgdG8gY2hpbGRyZW5cclxuXHRjaGlsZHJlbi5wdXNoKC4uLmZpbGVzV2l0aE1ldGFkYXRhKTtcclxuXHJcblx0Ly8gUHJvY2VzcyBzdWJmb2xkZXJzXHJcblx0aXRlbXNcclxuXHRcdC5maWx0ZXIoKGl0ZW0pID0+IGl0ZW0uaXNEaXJlY3RvcnkoKSlcclxuXHRcdC5mb3JFYWNoKChzdWJmb2xkZXIpID0+IHtcclxuXHRcdFx0Y29uc3Qgc3ViZm9sZGVyTmFtZSA9IHN1YmZvbGRlci5uYW1lO1xyXG5cdFx0XHRjb25zdCBzdWJmb2xkZXJQYXRoID0gcGF0aC5qb2luKGZvbGRlclBhdGgsIHN1YmZvbGRlck5hbWUpO1xyXG5cdFx0XHRjb25zdCBzdWJmb2xkZXJJdGVtcyA9IFtdO1xyXG5cclxuXHRcdFx0Ly8gR2V0IG1hcmtkb3duIGZpbGVzIGluIHRoZSBzdWJmb2xkZXJcclxuXHRcdFx0Y29uc3Qgc3ViZm9sZGVyRmlsZXNXaXRoTWV0YWRhdGEgPSBmcy5yZWFkZGlyU3luYyhzdWJmb2xkZXJQYXRoLCB7IHdpdGhGaWxlVHlwZXM6IHRydWUgfSlcclxuXHRcdFx0XHQuZmlsdGVyKChzdWJJdGVtKSA9PiBzdWJJdGVtLmlzRmlsZSgpICYmIHN1Ykl0ZW0ubmFtZS5lbmRzV2l0aCgnLm1kJykpXHJcblx0XHRcdFx0Lm1hcCgoc3ViRmlsZSkgPT4ge1xyXG5cdFx0XHRcdFx0Y29uc3QgbmFtZSA9IHN1YkZpbGUubmFtZS5yZXBsYWNlKCcubWQnLCAnJyk7XHJcblx0XHRcdFx0XHRpZiAobmFtZSAhPT0gJ1JFQURNRScpIHtcclxuXHRcdFx0XHRcdFx0Y29uc3QgZmlsZVBhdGggPSBwYXRoLmpvaW4oc3ViZm9sZGVyUGF0aCwgc3ViRmlsZS5uYW1lKTtcclxuXHRcdFx0XHRcdFx0Y29uc3QgdGl0bGUgPSBleHRyYWN0VGl0bGVGcm9tTWFya2Rvd24oZmlsZVBhdGgpID8/IENhc2UudGl0bGUobmFtZSk7XHJcblx0XHRcdFx0XHRcdGNvbnN0IHBvc2l0aW9uID0gZXh0cmFjdFBhZ2VQb3NpdGlvbkZyb21NYXJrZG93bihmaWxlUGF0aCk7XHJcblxyXG5cdFx0XHRcdFx0XHRyZXR1cm4ge1xyXG5cdFx0XHRcdFx0XHRcdHRleHQ6IHRpdGxlLFxyXG5cdFx0XHRcdFx0XHRcdGxpbms6IGAvJHtmb2xkZXJOYW1lfS8ke3N1YmZvbGRlck5hbWV9LyR7bmFtZX1gLFxyXG5cdFx0XHRcdFx0XHRcdHBvc2l0aW9uOiBwb3NpdGlvbiAhPT0gbnVsbCA/IHBhcnNlSW50KHBvc2l0aW9uLCAxMCkgOiBudWxsXHJcblx0XHRcdFx0XHRcdH07XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0cmV0dXJuIG51bGw7XHJcblx0XHRcdFx0fSlcclxuXHRcdFx0XHQuZmlsdGVyKEJvb2xlYW4pO1xyXG5cclxuXHRcdFx0Ly8gU29ydCBzdWJmb2xkZXIgZmlsZXMgYnkgcG9zaXRpb24gZmlyc3QsIHRoZW4gYnkgdGl0bGVcclxuXHRcdFx0c3ViZm9sZGVyRmlsZXNXaXRoTWV0YWRhdGEuc29ydCgoYSwgYikgPT4ge1xyXG5cdFx0XHRcdC8vIElmIGJvdGggaGF2ZSBwb3NpdGlvbnMsIHNvcnQgbnVtZXJpY2FsbHlcclxuXHRcdFx0XHRpZiAoYS5wb3NpdGlvbiAhPT0gbnVsbCAmJiBiLnBvc2l0aW9uICE9PSBudWxsKSB7XHJcblx0XHRcdFx0XHRyZXR1cm4gYS5wb3NpdGlvbiAtIGIucG9zaXRpb247XHJcblx0XHRcdFx0fVxyXG5cdFx0XHRcdC8vIElmIG9ubHkgYSBoYXMgcG9zaXRpb24sIGl0IGNvbWVzIGZpcnN0XHJcblx0XHRcdFx0aWYgKGEucG9zaXRpb24gIT09IG51bGwpIHtcclxuXHRcdFx0XHRcdHJldHVybiAtMTtcclxuXHRcdFx0XHR9XHJcblx0XHRcdFx0Ly8gSWYgb25seSBiIGhhcyBwb3NpdGlvbiwgaXQgY29tZXMgZmlyc3RcclxuXHRcdFx0XHRpZiAoYi5wb3NpdGlvbiAhPT0gbnVsbCkge1xyXG5cdFx0XHRcdFx0cmV0dXJuIDE7XHJcblx0XHRcdFx0fVxyXG5cclxuXHRcdFx0XHQvLyBJZiBuZWl0aGVyIGhhcyBwb3NpdGlvbiwgc29ydCBhbHBoYWJldGljYWxseVxyXG5cdFx0XHRcdHJldHVybiBhLnRleHQubG9jYWxlQ29tcGFyZShiLnRleHQpO1xyXG5cdFx0XHR9KTtcclxuXHJcblx0XHRcdC8vIEFkZCB0aGUgc29ydGVkIHN1YmZvbGRlciBmaWxlc1xyXG5cdFx0XHRzdWJmb2xkZXJJdGVtcy5wdXNoKC4uLnN1YmZvbGRlckZpbGVzV2l0aE1ldGFkYXRhKTtcclxuXHJcblx0XHRcdC8vIENoZWNrIGlmIHN1YmZvbGRlciBoYXMgUkVBRE1FIGZvciBpdHMgdGl0bGVcclxuXHRcdFx0Y29uc3Qgc3ViZm9sZGVyUmVhZG1lUGF0aCA9IHBhdGguam9pbihzdWJmb2xkZXJQYXRoLCAnUkVBRE1FLm1kJyk7XHJcblx0XHRcdGxldCBzdWJmb2xkZXJUaXRsZSA9IENhc2UudGl0bGUoc3ViZm9sZGVyTmFtZSlcclxuXHRcdFx0XHQucmVwbGFjZSgnSnMnLCAnSlMnKVxyXG5cdFx0XHRcdC5yZXBsYWNlKCdQaHAnLCAnUEhQJyk7XHJcblxyXG5cdFx0XHRpZiAoZnMuZXhpc3RzU3luYyhzdWJmb2xkZXJSZWFkbWVQYXRoKSkge1xyXG5cdFx0XHRcdGNvbnN0IGV4dHJhY3RlZFRpdGxlID0gZXh0cmFjdFRpdGxlRnJvbU1hcmtkb3duKHN1YmZvbGRlclJlYWRtZVBhdGgpO1xyXG5cdFx0XHRcdGlmIChleHRyYWN0ZWRUaXRsZSkge1xyXG5cdFx0XHRcdFx0c3ViZm9sZGVyVGl0bGUgPSBleHRyYWN0ZWRUaXRsZTtcclxuXHRcdFx0XHR9XHJcblx0XHRcdH1cclxuXHJcblx0XHRcdC8vIEFkZCB0aGUgc3ViZm9sZGVyIHdpdGggaXRzIGNoaWxkcmVuIGlmIGl0IGhhcyBhbnkgY29udGVudFxyXG5cdFx0XHRpZiAoc3ViZm9sZGVySXRlbXMubGVuZ3RoID4gMCkge1xyXG5cdFx0XHRcdGNoaWxkcmVuLnB1c2goe1xyXG5cdFx0XHRcdFx0dGV4dDogc3ViZm9sZGVyVGl0bGUsXHJcblx0XHRcdFx0XHRjb2xsYXBzaWJsZTogdHJ1ZSxcclxuXHRcdFx0XHRcdGNoaWxkcmVuOiBzdWJmb2xkZXJJdGVtc1xyXG5cdFx0XHRcdH0pO1xyXG5cdFx0XHR9XHJcblx0XHR9KTtcclxuXHJcblx0Ly8gU29ydCB0aGUgdG9wLWxldmVsIGl0ZW1zIC0gZm9sZGVycyBzdGlsbCBjb21lIGFmdGVyIGZpbGVzXHJcblx0cmV0dXJuIGNoaWxkcmVuLnNvcnQoKGEsIGIpID0+IHtcclxuXHRcdC8vIElmIGl0J3MgYSBmaWxlIHZzIHN1YmZvbGRlciwgZmlsZXMgY29tZSBmaXJzdFxyXG5cdFx0aWYgKCFhLmNoaWxkcmVuICYmIGIuY2hpbGRyZW4pIHJldHVybiAtMTtcclxuXHRcdGlmIChhLmNoaWxkcmVuICYmICFiLmNoaWxkcmVuKSByZXR1cm4gMTtcclxuXHJcblx0XHQvLyBJZiBib3RoIGFyZSBmaWxlcywgdGhleSd2ZSBhbHJlYWR5IGJlZW4gc29ydGVkIGJ5IHBvc2l0aW9uIGFuZCB0aXRsZVxyXG5cdFx0aWYgKCFhLmNoaWxkcmVuICYmICFiLmNoaWxkcmVuKSB7XHJcblx0XHRcdHJldHVybiAwOyAvLyBLZWVwIHRoZSBleGlzdGluZyBvcmRlciBmcm9tIG91ciBwcmV2aW91cyBzb3J0XHJcblx0XHR9XHJcblxyXG5cdFx0Ly8gSWYgYm90aCBhcmUgZm9sZGVycywgc29ydCBhbHBoYWJldGljYWxseVxyXG5cdFx0cmV0dXJuIGEudGV4dC5sb2NhbGVDb21wYXJlKGIudGV4dCk7XHJcblx0fSk7XHJcbn1cclxuXHJcbi8vIEZ1bmN0aW9uIHRvIGV4dHJhY3QgdGl0bGUgZnJvbSBtYXJrZG93biBmaWxlXHJcbmZ1bmN0aW9uIGV4dHJhY3RUaXRsZUZyb21NYXJrZG93bihmaWxlUGF0aCkge1xyXG5cdHRyeSB7XHJcblx0XHRjb25zdCBjb250ZW50ID0gZnMucmVhZEZpbGVTeW5jKGZpbGVQYXRoLCAndXRmOCcpO1xyXG5cclxuXHRcdC8vIExvb2sgZm9yIHRoZSBmaXJzdCBoZWFkaW5nIGluIHRoZSBmaWxlXHJcblx0XHRjb25zdCB0aXRsZU1hdGNoID1cclxuXHRcdFx0Y29udGVudC5tYXRjaCgvXnRpdGxlOlxccyooLispJC9tKSAvLyBNYXRjaCBZQU1MIGZyb250bWF0dGVyIHRpdGxlOiBUaXRsZVxyXG5cdFx0XHR8fCBjb250ZW50Lm1hdGNoKC9eI1xccysoLispJC9tKTsgLy8gTWF0Y2ggIyBUaXRsZVxyXG5cclxuXHRcdGlmICh0aXRsZU1hdGNoICYmIHRpdGxlTWF0Y2hbMV0pIHtcclxuXHRcdFx0cmV0dXJuIHRpdGxlTWF0Y2hbMV0udHJpbSgpO1xyXG5cdFx0fVxyXG5cclxuXHRcdHJldHVybiBudWxsO1xyXG5cdH1cclxuXHRjYXRjaCAoZXJyb3IpIHtcclxuXHRcdGNvbnNvbGUuZXJyb3IoYEVycm9yIHJlYWRpbmcgZmlsZSAke2ZpbGVQYXRofTpgLCBlcnJvcik7XHJcblxyXG5cdFx0cmV0dXJuIG51bGw7XHJcblx0fVxyXG59XHJcblxyXG5mdW5jdGlvbiBleHRyYWN0UGFnZVBvc2l0aW9uRnJvbU1hcmtkb3duKGZpbGVQYXRoKSB7XHJcblx0dHJ5IHtcclxuXHRcdGNvbnN0IGNvbnRlbnQgPSBmcy5yZWFkRmlsZVN5bmMoZmlsZVBhdGgsICd1dGY4Jyk7XHJcblxyXG5cdFx0Y29uc3QgcG9zaXRpb25NYXRjaCA9XHJcblx0XHRcdGNvbnRlbnQubWF0Y2goL15wb3NpdGlvbjpcXHMqKC4rKSQvbSk7IC8vIE1hdGNoIFlBTUwgZnJvbnRtYXR0ZXIgcG9zaXRpb246IG51bWJlclxyXG5cclxuXHRcdGlmIChwb3NpdGlvbk1hdGNoICYmIHBvc2l0aW9uTWF0Y2hbMV0pIHtcclxuXHRcdFx0cmV0dXJuIHBvc2l0aW9uTWF0Y2hbMV0udHJpbSgpO1xyXG5cdFx0fVxyXG5cclxuXHRcdHJldHVybiBudWxsO1xyXG5cdH1cclxuXHRjYXRjaCAoZXJyb3IpIHtcclxuXHRcdGNvbnNvbGUuZXJyb3IoYEVycm9yIHJlYWRpbmcgZmlsZSAke2ZpbGVQYXRofTpgLCBlcnJvcik7XHJcblxyXG5cdFx0cmV0dXJuIG51bGw7XHJcblx0fVxyXG59XHJcbiJdLAogICJtYXBwaW5ncyI6ICI7QUFBd1osU0FBUyxvQkFBb0I7QUFDcmIsU0FBUyx3QkFBd0I7QUFDakMsU0FBUyxtQkFBbUI7QUFDNUIsT0FBTyxVQUFVO0FBQ2pCLE9BQU8sUUFBUTtBQUNmLE9BQU8sVUFBVTtBQUNqQixTQUFTLHlCQUF5QjtBQUNsQyxTQUFTLHlCQUF5QjtBQUNsQyxTQUFTLHFCQUFxQjtBQUM5QixTQUFTLG9CQUFvQjtBQUM3QixTQUFTLHlCQUF5QjtBQVZsQyxJQUFNLG1DQUFtQztBQVl6QyxJQUFNLFVBQVUsS0FBSyxRQUFRLGtDQUFXLEtBQUs7QUFFN0MsSUFBTyxpQkFBUSxpQkFBaUI7QUFBQSxFQUMvQixNQUFNO0FBQUEsRUFFTixPQUFPO0FBQUEsRUFDUCxhQUFhO0FBQUEsRUFFYixRQUFRO0FBQUEsRUFDUixPQUFPLGFBQWE7QUFBQSxJQUNuQixNQUFNO0FBQUEsSUFDTixNQUFNO0FBQUEsSUFDTixXQUFXO0FBQUEsSUFDWCxRQUFRO0FBQUEsTUFDUDtBQUFBLE1BQ0E7QUFBQSxRQUNDLE1BQU07QUFBQSxRQUNOLE1BQU07QUFBQSxNQUNQO0FBQUEsTUFDQTtBQUFBLFFBQ0MsTUFBTTtBQUFBLFFBQ04sTUFBTTtBQUFBLE1BQ1A7QUFBQSxNQUNBO0FBQUEsUUFDQyxNQUFNO0FBQUEsUUFDTixNQUFNO0FBQUEsTUFDUDtBQUFBLE1BQ0E7QUFBQSxRQUNDLE1BQU07QUFBQSxRQUNOLE1BQU07QUFBQSxNQUNQO0FBQUEsSUFDRDtB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
